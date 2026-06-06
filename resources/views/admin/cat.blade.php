
@extends('admin.layout')
@section('title','Admin Catagory')

@section('content')
        <div class="container-fluid">
            <div class="row">
                <!-- Basic Form-->
                <div class="col-lg-5">
                    <div class="block">
                        <div class="title"><strong class="d-block">Add Category</strong><span class="d-block"></span></div>
                        <div class="block-body">
                            <form
                                action="{{ isset($editCat) ? url('update_cat/'.$editCat->id) : url('create_cat') }}"
                                method="POST">
                                @csrf

                                <div class="form-group">
                                    <label class="form-control-label">Category Name</label>
                                    <input
                                        type="text" name="cat_name" placeholder="Category Name" class="form-control" required value="{{ isset($editCat) ? $editCat->name : '' }}">
                                </div>
                                
                                <label class="label-material">Catagory Type</label>
                                <div class="col-sm-9">
                                    <select name="cat_type" class="form-control" required>
                                        <option>Select</option>
                                        <option value="Food" {{ (isset($editCat) && $editCat->type == 'Food') ? 'selected' : '' }}>Food</option>
                                        <option value="Drink" {{ (isset($editCat) && $editCat->type == 'Drink') ? 'selected' : '' }}>Drink</option>
                                        <option value="Ingredient" {{ (isset($editCat) && $editCat->type == 'Ingredient') ? 'selected' : '' }}>Ingredient</option>
                                    </select>
                                </div>
                                <div class="form-group">

                                </div>
                                <div class="form-group">
                                    <input
                                        type="submit" value="{{ isset($editCat) ? 'Update Category' : 'Add Category' }}" class="btn btn-primary">
                                    @if(isset($editCat))
                                    <a href="{{ url('category') }}" class="btn btn-secondary ml-2">
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
                                        <th>Category Name</th>
                                        <th>Category Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                @foreach($cat as $cat)

                                <tbody>
                                    <tr>
                                        <th>{{$cat->name}}</th>
                                        <th>{{$cat->type}}</th>
                                        <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                            <div class="dropdown-menu">

                                                <a href="{{ url('category?edit='.$cat->id) }}" class="dropdown-item">
                                                    Edit
                                                </a>
                                                <a href="" class="dropdown-item">Delete</a>

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