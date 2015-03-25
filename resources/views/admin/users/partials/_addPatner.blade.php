<a class="btn btn-info" data-toggle="modal" data-target="#modalAddPatner" id="btn-add-patner">{!! isset($buttonText) ? $buttonText : 'Agregar Patrocinador' !!}</a>

<div class="modal fade" id="modalAddPatner" tabindex="-1" role="dialog" aria-labelledby="modalAddPatnerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modalAddPatnerLabel">Patrocinadores</h4> <img class="loading-search hidden" src="/img/loading.gif" alt="Loading" />
      </div>
      <div class="modal-body">
        <form class="navbar-form navbar-search navbar-left dropdown " role="search">
	        <div class="form-group">
	          
	           <input id="searchText" type="text" class="form-control search-query dropdown-toggle " data-toggle="dropdown" placeholder="Search" autocomplete="off">
	        </div>
        
      </form>
		<table class="table table-striped  ">
	        <thead>
	            <tr>
	                <th>=</th>
	                <th>#</th>
	                <th>Nombre</th>
	               
	            </tr>
	        </thead>
	        <tbody class="tbody">
	            
	        </tbody>
	       <tfoot>
	          <tr>
	          	<td  colspan="10" class="pagination-container">
	          		<ul class="pagination"></ul>
	          	</td>
	          </tr>
	          
	             
	        </tfoot>
	    </table>
        <script id="patnersTemplate" type="text/x-handlebars-template">
 			   @{{#each this}}
				  <tr>
	            	    <td class="@{{ class }}"> <input type="radio" name="chkPatners" value="@{{ id }}" /></td>
	                    <td>@{{ id }}</td>
	                    <td>@{{ name }}</td>
	            	
	              </tr>
				@{{/each}}
	         
	          
	    </script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>