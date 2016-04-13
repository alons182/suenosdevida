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
@section('scripts')
    <script src="/js/vendor/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'ckeditor_info' , {
            uiColor: '#FAFAFA',
            allowedContent : true
        });
        CKEDITOR.replace( 'ckeditor_description' , {
            uiColor: '#FAFAFA',
            allowedContent : true
        });
        (function($) {

            setTimeout(function(){
                $('#province option[value="guanacaste"]').attr("selected", true);
                $('#province').change();

            }, 100);



        })(jQuery);
    </script>
@stop
