<div class="search">
    {!! Form::open(['route' => 'products_search','method' => 'get','class'=>'form-search']) !!}

         <button type="submit" class="btn-icon-search"><i class="icon-search"></i></button>

        {!! Form::text('q',isset($search) ? $search : null ,['class'=>'form-control','placeholder'=>'Buscar'])!!}

     {!! Form::close() !!}


</div>