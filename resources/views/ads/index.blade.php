@extends('layouts.layout')

@section('content')
<section class="main payments">
    <h1>Publicidad </h1>

    <div class="table-responsive payments-table">

        <table class="table table-striped  ">
            <thead>
            <tr>

                <th>#</th>
                <th>Nombre Publicidad</th>
                <th>Visto</th>
                <th>=</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($ads as $ad)
            <tr>

                <td>{!! $ad->id !!}</td>
                <td>{!! $ad->name !!}</td>
                <td>{!! ($ad->hits->count() > 0) ? 'Si' : 'No' !!}</td>

                <td>{!! ($ad->hits->count() > 0) ? '--' : link_to_route('ads.show', 'Ver publicidad', $ad->id) !!}</td>
            </tr>
            @empty
             <tr><td colspan="4" style="text-align: center;">No hay publicidad para ver</td></tr>
            @endforelse
            </tbody>
            <tfoot>

            @if ($ads)
                <td  colspan="4" class="pagination-container">{!!$ads->render()!!}</td>
            @endif


            </tfoot>
        </table>


    </div>

</section>

@stop