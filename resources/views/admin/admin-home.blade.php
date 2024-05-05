@extends('layouts.app')
@extends('layouts.admin-navbar')
<form id="logout-form" action="{{ route('logout') }}" method="POST" >
    @csrf
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout 
    </a>
</form>


