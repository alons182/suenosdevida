@extends('layouts.layout')

@section('content') 
    <h1>Descargas</h1>


	<div class="table-responsive">

        <table class="table table-striped  ">
        <thead>
            <tr>

                <th>Tipo</th>
                <th>Nombre</th>

                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($files as $file)
                <tr>

                    <td>{!! $file['type'] !!}</td>
                    <td>{!! $file['name'] !!}</td>


                    <td>

                       <a href="/downloads_files/{!! $file['name'] !!}" class="btn btn-danger btn-sm">Descargar</a>

                    </td>

                </tr>
            @endforeach
        </tbody>
       <tfoot>

        </tfoot>
    </table>
    {!! Form::close() !!}

    </div>


@stop