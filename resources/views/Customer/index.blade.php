@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Customer</h2>
            </div>
            <div class="pull-right">
                @can('customer-create')
                <a class="btn btn-success" href="{{ route('customer.create') }}"> Create New Customer</a>
                @endcan
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Remarks</th>
            <th>Status</th>
            @canany(['customer-edit','customer-delete','customer-assign'])
            <th width="280px">Action</th>
            @endcan
        </tr>
	    @foreach ($customers as $customer)
	    <tr>
	        <td>{{ ++$i }}</td>
            <td class="customer_id" style="display:none">{{ $customer->customer_id }}</td>
	        <td>{{ $customer->name }}</td>
	        <td>{{ $customer->phone }}</td>
            <td>{{ $customer->email }}</td>
            @can('customer-follow-up')
            <td>
                <a href="" class="updateRemarks" data-name="remarks" data-type="text" data-pk="{{ $customer->customer_id }}" data-title="Enter Remarks">{{ $customer->remarks ?? 'No Remarks'}}</a>
            </td>
            @else
                <td>{{ $customer->remarks ?? 'No Remarks' }}</td>
            @endcan
            @can('customer-follow-up')
            <td>
                {!! Form::select('status', array('0' => 'Uncontacted', '1' => 'Pending' , '2' => 'Qualified' , '3' => 'Lost'), $customer->status , ['id' => 'status']); !!}
            </td>
            @else
            <td>
                @if($customer->status == 0)
                Uncontacted
                @elseif($customer->status == 1)
                Pending
                @elseif($customer->status == 2)
                Qualified
                @else
                Lost
                @endif
            </td>
            @endcan
            
            @canany(['customer-edit','customer-delete','customer-assign'])
	        <td>
                <form action="{{ route('customer.destroy',$customer->customer_id) }}" method="POST">
                    @can('customer-edit')
                    <a class="btn btn-primary" href="{{ route('customer.edit',$customer->customer_id) }}">Edit</a>
                    @endcan

                    @csrf
                    @method('DELETE')
                    @can('customer-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan
                    @can('customer-assign')
                    {!! Form::Label('agents', 'Assign To:') !!}
                    {!! Form::select('agents', $agents, ['class' => 'form-control assign-agent']) !!}
                    @endcan
                </form>
	        </td>
            @endcan
	    </tr>
	    @endforeach
    </table>

    {!! $customers->links() !!}

    <script type="text/javascript">
    $.fn.editable.defaults.mode = 'inline';
  
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    }); 
  
    $('.updateRemarks').editable({
           url: "{{ route('customer.updateRemarks') }}",
           type: 'text',
           pk: 1,
           name: 'remarks',
           title: 'Enter Remarks'
    });

    ////Change Status
    $('#status').change(function(){
       var status = $(this).val();
       var customer_id = $(this).closest('tr').children('td.customer_id').text();
       $.ajax({
          url : '{{ route( 'customer.updateStatus' ) }}',
          data: {
            "_token": "{{ csrf_token() }}",
            "customer_id": customer_id ,
            "status": status
            },
          type: 'post',
          dataType: 'json',
          success: function( result )
          {
              
          }
       });
    });

    ////Assign Agent
    $('#agents').change(function(){
       var agent_id = $(this).val();
       var customer_id = $(this).closest('tr').children('td.customer_id').text();
       $.ajax({
          url : '{{ route( 'admin.assignAgent' ) }}',
          data: {
            "_token": "{{ csrf_token() }}",
            "agent_id": agent_id ,
            "customer_id": customer_id
            },
          type: 'post',
          dataType: 'json',
          success: function( result )
          {
              
          }
       });
    });
    </script>

@endsection