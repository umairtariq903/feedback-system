@extends('admin.layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <
            <div class="col-lg-6 mx-auto">
                @if(session("error"))
                    <div class="alert alert-danger">{{session('error')}}</div>
                @endif
                <!-- Form -->
                <form class="custom-form" method="post" action="{{route('admin.feedback_update',["id"=>$feedback->id])}}">
                    @csrf
                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                        <input value="{{$feedback->title}}" type="text" name="title" class="form-control" id="title"
                               placeholder="Enter title">
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                        <textarea name="description" class="form-control" id="description" rows="3"
                                  placeholder="Enter description">{{$feedback->description}}</textarea>
                    </div>

                    <!-- Category Field -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category:</label>
                        <x-input-error :messages="$errors->get('category')" class="mt-2"/>
                        <select name="category" class="form-control">

                            <option @if($feedback->category=="Bug Report") selected @endif value="Bug Report"> Bug Report</option>
                            <option @if($feedback->category=="Feature Request") selected @endif value="Feature Request"> Feature Request</option>
                            <option @if($feedback->category=="Improvement") selected @endif value="Improvement">Improvement</option>

                        </select>

                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="background: #1976D2">Update</button>
                </form>

            </div>
        </div>

    </div>
@endsection
