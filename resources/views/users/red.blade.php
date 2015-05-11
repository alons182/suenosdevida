@extends('layouts.layout')

@section('content')
<h1>{!! $currentUser->username !!} | <small>{!! $currentUser->profiles->present()->fullname !!}</small> <small class="level-{!! $currentUser->level !!}"> Nivel: {!! $currentUser->level !!}</small> </h1>

<h2>Tu red de usuarios</h2>

@for($i = 1; $i<=$currentUser->level; $i++)
    @forelse ($currentUser->immediateDescendants()->with(array('payments' => function($query) use ($month, $year)
                                                         {
                                                              $query->where(\DB::raw('MONTH(created_at)'), '=', $month)
                                                                ->where(\DB::raw('YEAR(created_at)'), '=', $year);

                                                      }))->with('profiles','children')->get()->chunk(10) as $userSet)

        <div class="row users level-{!! $i !!}">
            @foreach ($userSet as $user)
                <div class="col-md-3 user-block">

                    <div class="user-icon-toggle">
                        <i class="icon-user"></i>
                    </div>
                     <div class="user-block-info hidden">
                        <p><b>Usuario :</b> {!!  $user->username !!}</p>
                        <p> <b>Correo :</b>  <a href="mailto:{!! $user->email !!}">{!! $user->email !!}</a></p>
                        <p> <b>Telefono :</b>  {!! $user->profiles->telephone !!}</p>
                        <p><b>Afiliados :</b>  {!! $user->children->count() !!}</p>
                         <p><span class="level-{!! $user->level !!}"><b>Nivel :</b>  {!! $user->level !!}</span></p>
                       <p> <b>Pago membresia :</b>  {!! ($user->payments->count() > 0) ? 'Si' : 'NO'!!}</p>

                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <p>No tiene usuarios en tu red</p>
    @endforelse
@endfor





<p>
    @if ($currentUser->isCurrent())
    {!! link_to_route('profile.edit', 'Edit your Profile', $currentUser->username) !!}
    @endif
</p>

@stop