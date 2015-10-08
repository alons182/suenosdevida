 <div class="well well-large actions">
           
            <div class="filtros">
               
               
                {!! Form::open(['route' => 'store.admin.orders.index','method' => 'get']) !!}
                   <div class="form-group">
                        <div class="controls">
                            {!! Form::label('q', 'Buscar') !!}
                            {!! Form::text('q',$search, ['class'=>'form-control'] ) !!}
                        </div>
                        <div class="controls">
                            {!! Form::label('status', 'Estado') !!}
                            {!! Form::select('status', ['' => '-- Seleccionar --','P' => 'Pendiente','F' => 'Finalizada'], $selectedStatus, ['class'=>'form-control'] ) !!}
                        </div>
                        
                 </div>  
                {!! Form::close() !!}

            </div>
</div> 