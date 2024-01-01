@extends('admin.layouts.app')
@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .custom-table {
            background-color: #ffffff;
        }
    </style>
    <div class="container my-3">
        <form class="row" method="get" action="{{route('admin.dashboard')}}">
            <div class="col-md-4 mb-3">
                <select name="category" class="form-control w-50">
                    <option value="" selected disabled>Select Category</option>
                    <option value="Bug Report">Bug Report</option>
                    <option value="Feature Request">Feature Request</option>
                    <option value="Improvement">Improvement</option>
                </select>
            </div>
            <div class="col-md-4 text-start">
                <button type="submit" class="btn btn-info">Filter</button>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        @if(session("success"))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif

        <table class="table custom-table">
            <thead>
            <tr>
                <th scope="col">Category</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">User Name</th>
                <th scope="col">Total Vote</th>
                <th scope="col">Total Comment</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($feedback as $row)
                <tr>
                    <td>{{$row->category}}</td>
                    <td>{{$row->title}}</td>
                    <td>{{$row->description}}</td>
                    <td>{{$row->users->name}}</td>
                    <td>{{$row->vote}}</td>
                    <td>{{$row->comment->count()}}</td>
                    <td>
                        <a href="{{route('admin.delete',["id"=>$row->id])}}"
                           class="btn btn-danger btn-sm my-1">Delete</a>
                        <a href="{{route('admin.edit',["id"=>$row->id])}}" class="btn btn-info btn-sm my-1">Edit</a>
                    </td>
                </tr>

            @empty
            @endforelse

            </tbody>
        </table>
        <div class="mt-3 d-flex justify-content-center">
            {{$feedback->links('pagination::bootstrap-4')}}
        </div>
    </div>
@endsection
