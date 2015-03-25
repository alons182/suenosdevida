@extends('layouts.layout')

@section('content')
    <h1>{{ $currentUser->username }} | <small>{{ $currentUser->profiles->present()->fullname }}</small></h1>
    <small>{{ $currentUser->present()->accountAge }} </small>

    <h2>Tu red de usuarios</h2>
    @foreach ($currentUser->descendants()->get() as $child)
           <li>{{ get_depth($child->depth)}}  {{ $child->username }} - <small>{{ $child->children->count() }}</small>  </li>
    @endforeach

    <p>
        @if ($currentUser->isCurrent())
        {{ link_to_route('profile.edit', 'Edit your Profile', $currentUser->username) }}
        @endif
    </p>

@stop