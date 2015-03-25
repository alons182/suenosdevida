 <div class="filters">

               
                {!! Form::open(['route' => ['products_path',$category],'method' => 'get']) !!}
                <div class="col-1">
                     <div class="form-group">

                            <div class="controls">
                                {!! Form::label('subcat', 'Filtro') !!}
                                {!! Form::select('subcat', ['' => '-- Seleccionar --'] + $subcategories, $selected, ['class'=>'form-control'] ) !!}
                            </div>


                     </div>
                </div>
                {!! Form::close() !!}

 </div>