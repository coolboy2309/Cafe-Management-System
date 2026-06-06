@extends('admin.layout')
@section('title','Admin Unit')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Basic Form-->
        <div class="col-lg-5">
            <div class="block">
                <div class="title"><strong class="d-block">Add Unit</strong><span class="d-block"></span></div>
                <div class="block-body">
                    <form
                        action="{{ isset($editunit) ? url('update_unit/'.$editunit->id) : url('create_unit') }}"
                        method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-control-label">Unit Name</label>
                            <input
                                type="text" name="unit_name" placeholder="Unit Name" class="form-control" required value="{{ isset($editunit) ? $editunit->name : '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Unit Symbol</label>
                            <input
                                type="text" name="unit_symbol" placeholder="Unit Symbol" class="form-control" required value="{{ isset($editunit) ? $editunit->symbol : '' }}">
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <input
                                type="submit" value="{{ isset($editunit) ? 'Update Utni' : 'Add Unit' }}" class="btn btn-primary">
                            @if(isset($editunit))
                            <a href="{{ url('unit') }}" class="btn btn-secondary ml-2">
                                X
                            </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- Horizontal Form-->

        <div class="col-lg-7">
            <div class="block">
                <div class="title"><strong class="d-block">Category</strong>
                    <p>
                        @if(session()->has('message'))
                        {{session()->get('message')}}
                        @endif
                    </p>
                </div>
                <div class="block-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Unit Name</th>
                                <th>Unit Symbol</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach($unit as $unit)

                        <tbody>
                            <tr>
                                <th>{{$unit->name}}</th>
                                <th>{{$unit->symbol}}</th>
                                <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                    <div class="dropdown-menu">

                                        <a href="{{ url('unit?edit='.$unit->id) }}" class="dropdown-item">
                                            Edit
                                        </a>
                                        <a href="{{url('delete_unit',$unit->id)}}" class="dropdown-item">Delete</a>

                                        <a href="" class="dropdown-item">Password Change</a>
                                        <a href="" class="dropdown-item">Blah</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">Separated link</a>
                                    </div>
                                </td>
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