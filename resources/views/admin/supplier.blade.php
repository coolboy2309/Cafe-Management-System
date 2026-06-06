@extends('admin.layout')
@section('title','Admin Supplier')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Basic Form-->
        <div class="col-lg-5">
            <div class="block">
                <div class="title"><strong class="d-block">Add Supplier</strong><span class="d-block"></span></div>
                <div class="block-body">
                    <form
                        action="{{ isset($edit_s) ? url('update_s/'.$edit_s->id) : url('create_s') }}"
                        method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-control-label">Supplier Name</label>
                            <input
                                type="text" name="s_name" placeholder="Supplier Name" class="form-control" required value="{{ isset($edit_s) ? $edit_s->name : '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Supplier Email</label>
                            <input
                                type="email" name="s_email" placeholder="Supplier Email" class="form-control" required value="{{ isset($edit_s) ? $edit_s->email : '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Supplier Phone</label>
                            <input
                                type="number" name="s_phone" placeholder="Supplier Phone" class="form-control" required value="{{ isset($edit_s) ? $edit_s->phone : '' }}">
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <input
                                type="submit" value="{{ isset($edit_s) ? 'Update Supplier' : 'Add Supplier' }}" class="btn btn-primary">
                            @if(isset($edit_s))
                            <a href="{{ url('supplier') }}" class="btn btn-secondary ml-2">
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
                <div class="title"><strong class="d-block">Supplier</strong>
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
                                <th>Supplier Name</th>
                                <th>Supplier Email</th>
                                <th>Supplier phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach($supplier as $s)

                        <tbody>
                            <tr>
                                <th>{{$s->name}}</th>
                                <th>{{$s->email}}</th>
                                <th>{{$s->phone}}</th>
                                <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                    <div class="dropdown-menu">

                                        <a href="{{ url('supplier?edit='.$s->id) }}" class="dropdown-item">
                                            Edit
                                        </a>
                                        <a href="{{url('delete_s',$s->id)}}" class="dropdown-item">Delete</a>

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