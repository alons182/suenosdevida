@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Ad Creation</h1>
	
	{!! Form::open(['route'=>'store.admin.ads.store','files'=> true]) !!}
		
		@include('admin/ads/partials/_form')
		
	{!! Form::close() !!}
</div>
@stop
@section('scripts')
    <script>
        (function($) {

            setTimeout(function(){
                $('#province option[value="guanacaste"]').attr("selected", true);
                $('#province').change();

            }, 100);



        })(jQuery);
    </script>
@stop
