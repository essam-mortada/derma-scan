@extends('layouts.app')
<form id="logout-form" action="{{ route('logout') }}" method="POST" >
    @csrf
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout 
    </a>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>display name</th>
            <th>type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->display_name }}</td>
                <td>{{ $user->type }}</td>
                <td>
                    <!-- Add links or buttons to edit or delete the user -->
                    <form action="{{ route('user.delete', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

