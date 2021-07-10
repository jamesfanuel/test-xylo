@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>History Log Customer</h2>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Agent Name</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Input Date</th>
        </tr>
	    @foreach ($data as $dt)
	    <tr>
	        <td>{{ ++$i }}</td>
	        <td>{{ $dt->agent_name }}</td>
	        <td>{{ $dt->customer_name }}</td>
	        <td>{{ $dt->phone }}</td>
            <td>{{ $dt->email }}</td>
            <td>{{ $dt->remarks ?? 'No Remarks' }}</td>
            <td>
                @if($dt->status == 0)
                Uncontacted
                @elseif($dt->status == 1)
                Pending
                @elseif($dt->status == 2)
                Qualified
                @else
                Lost
                @endif
            </td>
            <td>{{ $dt->created_at }}</td>
	    </tr>
	    @endforeach
    </table>

    {!! $data->links() !!}

@endsection