
$(function () {
    'use strict';

    $.ajaxOptions = {data: { _token: $('input[name=_token]').val() } };
    $('.file-inputs').bootstrapFileInput();

    $('#fileupload').fileupload({
        formData: {_token: $('input[name="_token"]').val()},
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'downloads/store'
    });
    $('#fileuploadBanners').fileupload({
        formData: {_token: $('input[name="_token"]').val()},
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'banners/store'
    });

    $('table[role="presentation"]').on('click','.delete', function(e){

       $(this).parents('.template-download').addClass('fade').removeClass('in');

    });

});