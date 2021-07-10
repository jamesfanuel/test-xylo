<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\AgentCustomerRelation;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {       
        $this->middleware('permission:customer-list|customer-create|customer-edit|customer-delete|customer-assign|customer-follow-up', ['only' => ['index','show']]);
        $this->middleware('permission:customer-create', ['only' => ['create','store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
        $this->middleware('permission:customer-assign', ['only' => ['assign']]);
        $this->middleware('permission:customer-follow-up', ['only' => ['updateRemarks','follow_up']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('Admin')){
            $customers = DB::table('ms_customer')
            ->select(
            'ms_customer.id as customer_id' 
            , 'ms_customer.agent_id' 
            , 'ms_customer.name'
            , 'ms_customer.phone'
            , 'ms_customer.email'
            , 'ms_customer.remarks'
            , 'ms_customer.status'
            , 'ms_customer.created_at')
            ->latest()
            ->paginate(5);
        }else{
            $customers = DB::table('ms_customer')
            ->select(
            'ms_customer.id as customer_id' 
            , 'ms_customer.agent_id'
            , 'ms_customer.name'
            , 'ms_customer.phone'
            , 'ms_customer.email'
            , 'ms_customer.remarks'
            , 'ms_customer.status'
            , 'ms_customer.created_at')
            ->where('ms_customer.agent_id',Auth::user()->id)
            ->latest()
            ->paginate(5);
        }
        $agents = User::role('Agent')->get();
        return view('customer.index',compact('customers','agents'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function updateRemarks(Request $request)
    {
        if ($request->ajax()) {
            Customer::find($request->pk)
                ->update([
                    $request->name => $request->value
                ]);
  
            return response()->json(['success' => true]);
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function updateStatus(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        if($customer) {
            $customer->status = $request->status;
            $customer->save();
        }

        $data = Customer::find($request->customer_id);

        DB::table('tr_agent_follow_up')->insert([
            'customer_id' => $data->id
            , 'agent_id' => $data->agent_id
            , 'status' => $data->status
            , 'remarks' => $data->remarks
            , 'created_at' => now()
            ]);

        return redirect()->route('customer.index')
            ->with('customer','Customer created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
    
        Customer::create($request->all());
    
        return redirect()->route('customer.index')
                        ->with('customer','Customer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return view('customer.show',compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customer.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        request()->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
    
        $customer->update($request->all());
    
        return redirect()->route('customer.index')
                        ->with('success','Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    
        return redirect()->route('customer.index')
                        ->with('success','Customer deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function followUpHistory(Customer $customer)
    {
        $data = DB::table('tr_agent_follow_up')
        ->select(
        'users.name as agent_name'
        , 'ms_customer.name as customer_name'
        , 'ms_customer.phone'
        , 'ms_customer.email'
        , 'ms_customer.remarks'
        , 'ms_customer.status'
        , 'tr_agent_follow_up.created_at')
        ->leftjoin('ms_customer','tr_agent_follow_up.customer_id','=','ms_customer.id')
        ->leftjoin('users','tr_agent_follow_up.agent_id','=','users.id')
        ->latest()
        ->paginate(5);
        return view('customer.history',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
