@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Shop Creation</h1>
	
	{!! Form::open(['route'=>'store.admin.shops.store','files'=> true]) !!}
		
		@include('admin/shops/partials/_form')
		
	{!! Form::close() !!}
</div>
@stop
@section('scripts')
	<script src="/js/vendor/ckeditor/ckeditor.js"></script>
	<script>

		CKEDITOR.replace( 'ckeditorInfo' , {
			uiColor: '#FAFAFA',
			allowedContent : true
		});
		CKEDITOR.replace( 'ckeditorDetails' , {
			uiColor: '#FAFAFA',
			allowedContent : true
		});
	</script>
@stop

