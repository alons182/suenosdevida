<div class="well well-large actions">
           
            <div class="filtros">
               
               
                {!! Form::open(['route' => 'store.admin.users.index','method' => 'get']) !!}
                   <div class="form-group">
                        <div class="controls">
                            {!! Form::label('q', 'Buscar') !!}
                            {!! Form::text('q',$search, ['class'=>'form-control'] ) !!}
                        </div>
                        <div class="controls">
                            {!! Form::label('active', 'Estado') !!}
                            {!! Form::select('active', ['' => '-- Seleccionar --','0' => 'Inactivo','1' => 'Activo'], $selectedStatus, ['class'=>'form-control'] ) !!}
                        </div>

                    </div>  
                {!! Form::close() !!}

            </div>
             @include('admin/users/partials/_export')

</div> 