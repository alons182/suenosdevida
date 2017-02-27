(function($){
    var menu = $('.menu'),
        btnMovil = $('#btn_nav'),
        btnLogin = $('.btn-login'),
        subcat = $('#subcat'),
        filters = $('.filters');


    $('.btn-menu').on('click',function(e){
        menu.toggle();
    });
    menu.find(".parent").hoverIntent({
        over: function() {
            $(this).find(">.sub-menu").slideDown(200 );
        },
        out:  function() {
            $(this).find(">.sub-menu").slideUp(200);
        },
        timeout: 200

    });

    $('.btn-catalogue-query').on('click',function(e){
        e.preventDefault();
        
        $('.shop-catalogues-query').toggle();
    });

    //menu.find('.item-109 a').addClass('btn btn-red');

    // Login form
    btnLogin.click(function(e){
        $('.login-register').toggle();
        e.preventDefault();
    });
    // NAV MOBILE
    btnMovil.click(function(){
        menu.toggle();

    });

    $('button[data-dismiss], input[data-dismiss]').on('click', function(e){
        $('.alert').fadeOut(300);
    });

    $('select[name="month"]').change(function() {

        $('.months').find('form').submit();

    });
    $('select[name="year"]').change(function() {

        $('.months').find('form').submit();

    });

    $easyzoom = $('.easyzoom').easyZoom();
    api = $easyzoom.data('easyZoom');

    $('.user-block .user-icon-toggle').on('click', function(e){
        //$('.user-block-info').addClass('hidden');
        $(this).next().toggleClass('hidden');

        e.preventDefault();
    });

    $('.datepicker').pickadate({
        monthsFull: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
        monthsShort: ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'],
        weekdaysFull: ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
        weekdaysShort: ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'],
        today: 'hoy',
        clear: 'borrar',
        close: 'cerrar',
        firstDay: 1,
        format: 'dddd d !de mmmm !de yyyy',
        formatSubmit: 'yyyy-mm-dd'
    });

    //GALLERY IMG CLICK

    $('.additional-images').find('img').on('click',function(){

        //console.log($(this).attr('src'));
        $('.main-image').find('a').attr('href', $(this).data('src')).find('img').attr('src', $(this).data('src'));

        api.swap($(this).data('src'), $(this).data('src'))

        //resizes();
       // if(main_image == true)
           // ResizeImageContainer($('.main-image'));

    });


    simpleCart({
        currency: "CRC",
        //Setting the Cart Columns for the sidebar cart display.
        cartColumns: [
            //A custom cart column for putting the quantity and increment and decrement items in one div for easier styling.

            //Name of the item
            { view: "remove" , text: "Borrar" , label: "Opciones" },
            { attr: "name" , label: "Articulo" },
            { view: 'currency', attr: "price" , label: "Precio" },
            {view:'input', attr:'quantity', label: "Cantidad" },
            //Subtotal of that row (quantity of that item * the price)
            { view: 'currency', attr: "total" , label: "Total"  },


        ],
        cartStyle: 'div',
        checkout: {
            type: "SendForm" ,
            url: "/cart/checkout"
           /* extra_data: {
                _token: $('input[name=_token]').val()

            }*/

        },
        // event callbacks
        beforeAdd               : null,
        afterAdd                : null,
        load                    : null,
        beforeSave              : null,
        afterSave               : null,
        update                  : null,
        ready                   : null,
        checkoutSuccess         : null,
        checkoutFail                : '/cart',
        beforeCheckout              : function( data ){
            data.invoiceNumber = "ABC-123456788";
            data._token = $('input[name=_token]').val()
            var items = [];
            simpleCart.each(function( item , x ){
                items.push( item.get('id') );

            });
            data.items = items;


        }
    });
    simpleCart.currency({
        code: "CRC" ,
        name: "Colon" ,
        symbol: "₡"
    });


    $(".cart .cartInfo").on('click', function(){
        $("#cartPopover").toggle();

        if( $(this).hasClass('open') )
            $(this).removeClass('open');
        else
            $(this).addClass('open');
    });

    subcat.change(function() {

        filters.find('form').submit();

    });


    var provincias = $('#province'),
        cantones = $('#canton'),
        ubicaciones = window.ubicaciones;

    provincias.empty();
    cantones.empty();

    //provincias.append('<option value=""></option>');
    $.each(ubicaciones, function(index,provincia) {
        provincias.append('<option value='+ provincia.name_id +'>' + provincia.title + '</option>');
    });

    provincias.change(function() {
        var $this =  $(this);
        cantones.empty();
        $.each(ubicaciones, function(index,provincia) {
            if(provincia.name_id == $this.val())
                $.each(provincia.cantones, function(index,canton) {
                    cantones.append('<option value=' + canton.name_id + '>' + canton.title + '</option>');
                });
        });

    });


})(jQuery);
