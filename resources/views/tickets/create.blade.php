

@extends('layouts.app')

@section('content')

<h2>Create Ticket</h2>

<form method="POST" action="/tickets">
    @csrf

    <input type="text" name="title" class="form-control mb-2" placeholder="Title">

    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

    <select name="priority" class="form-control mb-2">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select>

    <button class="btn btn-success">Submit</button>
</form>

@endsection