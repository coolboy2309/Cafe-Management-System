@extends('admin.layout')
@section('title','Expense')

@section('top')

<div class="block-body text-right">
    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Expense</button>
    <!-- Modal-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Expense </strong>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <form action="{{url('data_expense')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-control-label">Reason</label>
                            <input type="text" name="name" placeholder="Reason of Expense" class="form-control">
                        </div>
                        <label class="label-material">Select Payment Method</label>
                        <div class="col-sm-13">
                            <select name="type" class="form-control mb-8 mb-8" validate>
                                <option style="background-color: #2f2f2fff">Select Paymnt Type</option>
                                <option style="background-color: #2f2f2fff" value="Cash">Cash</option>
                                <option style="background-color: #2f2f2fff" value="Bank">Bank</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Amount Birr</label>
                            <input type="number" name="amount" placeholder="Amount" class="form-control">
                        </div>
                        <div class="form-group">

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <input type="submit" value="Report" class="btn btn-primary">

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div><strong>
                        @if(session()->has('message'))
                        <span style="color:Green;margin:5px">{{session()->get('message')}}</span>
                        @endif
                    </strong></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Reason</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Register At</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>

                        @foreach($data as $data)

                        <tbody>
                            <tr>
                                <th>{{$data->name}}</th>
                                <th>{{$data->type}}</th>
                                <th>{{$data->amt}}</th>
                                <th>{{$data->created_at->format('M d, Y')}}</th>
                                <!-- <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                    <div class="dropdown-menu">

                                        <a href="{{url('edit_user',$data->id)}}" class="dropdown-item">Edit</a>

                                        <a href="{{url('delete_user',$data->id)}}" class="dropdown-item">Delete</a>
                                        @if($data->active == 0)
                                        <a href="{{url('active',$data->id)}}" class="dropdown-item">Active</a>
                                        @endif
                                        @if($data->active == 1)
                                        <a href="{{url('deactive',$data->id)}}" class="dropdown-item">Deactive</a>
                                        @endif


                                        <a href="{{url('change_pass',$data->id)}}" class="dropdown-item">Password Change</a>
                                        <a href="" class="dropdown-item">Blah</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">Separated link</a>
                                    </div>
                                </td> -->
                            </tr>

                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection