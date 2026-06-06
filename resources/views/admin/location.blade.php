@extends('admin.layout')
@section('title','Location')

@section('content')


<div class="container-fluid">
    <div class="row">
        <!-- Basic Form-->
        <div class="col-lg-5">
            <div class="block">
                <div class="title"><strong class="d-block">Add Location</strong><span class="d-block"></span></div>
                <div class="block-body">
                    <form
                        action="{{ isset($editloc) ? url('update_loc/'.$editloc->id) : url('create_loc') }}"
                        method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-control-label">Location Name</label>
                            <input
                                type="text" name="name" placeholder="Location Name" class="form-control" required value="{{ isset($editloc) ? $editloc->name : '' }}">
                        </div>

                        <label class="label-material">Catagory Type</label>
                        <div class="col-sm-13">
                            <select name="type" class="form-control" required>
                                <option>Select Location</option>
                                <option value="storage" {{ (isset($editloc) && $editloc->type == 'storage') ? 'selected' : '' }}>Storage</option>
                                <option value="consumption" {{ (isset($editloc) && $editloc->type == 'consumption') ? 'selected' : '' }}>Consumption</option>
                            </select>
                        </div>

                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <input
                                type="submit" value="{{ isset($editloc) ? 'Update Location' : 'Add Location' }}" class="btn btn-primary">
                            @if(isset($editloc))
                            <a href="{{ url('location') }}" class="btn btn-secondary ml-2">
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
                <div class="title"><strong class="d-block">Location`s</strong>
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
                                <th>Name</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach($loc as $unit)

                        <tbody>
                            <tr>
                                <th>{{$unit->name}}</th>
                                <th>{{$unit->type}}</th>
                                <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                    <div class="dropdown-menu">

                                        <a href="{{ url('location?edit='.$unit->id) }}" class="dropdown-item">
                                            Edit
                                        </a>
                                        <a href="{{url('delete_loc',$unit->id)}}" class="dropdown-item">Delete</a>

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