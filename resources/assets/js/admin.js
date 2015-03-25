$(function () {
	
    var cat = $( "#cat" ),
        published = $( "#published"),
        status = $( "#status"),
        active = $( "#active"),
        month = $( "#month"),
        year = $( "#year"),
        filters = $(".filtros"),
        gallery = $('#gallery'),
        infoBox = $('#InfoBox'),
        photos  = 0,
        chkProducts = $('.chk-product'),
        btnDeleteMultiple = $('.delete-multiple'),
        inputsPhotos = $("#inputs_photos"),
        inputsSizes = $("#inputs-sizes"),
        inputsColors = $("#inputs-colors"),
        colorfield =  $('.colorfield'),
        patners;


    $("form[data-confirm]").submit(function() {
        if ( ! confirm($(this).attr("data-confirm"))) {
            return false;
        }
    });
    /*$.fn.editable.defaults.ajaxOptions = {type: "PUT",data: { _token: $('input[name=_token]').val() } };
    $('.x-edit').editable();*/
    
    setTimeout(function(){
        $('.flash-message').fadeOut();
     }, 2000);

    cat.change(function() {

        filters.find('form').submit();

    });

    published.change(function() {
        
        filters.find('form').submit();

    });

    status.change(function() {

        filters.find('form').submit();

    });

    active.change(function() {

        filters.find('form').submit();

    });
    month.change(function() {

        filters.find('form').submit();

    });
    year.change(function() {

        filters.find('form').submit();

    });

       
     chkProducts.on('click',function(e) {
        
        (verificaChkActivo(chkProducts)) ? btnDeleteMultiple.show('fast') : btnDeleteMultiple.hide('fast');


     });

     function verificaChkActivo (chks) {
        var state = false;
        
        chks.each(function(){ 
            
            if(this.checked)
            {
              
             state = true;

             
            }

        });

        return state;
     }

   

    $("#add_input_photo").on('click', function (e) {
        photos++;
       
       inputsPhotos.append('<div><strong>Foto' + photos + ': </strong>'+
                                  '<input type="file" name="new_photo_file[]" size="45" /></div><br />');
        
    });

    $("#add_input_size").on('click', function (e) {
        
       inputsSizes.append('<div class="col-xs-3">'+
                           '<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>'+
                           '<input type="text" name="sizes[]" class="form-control " /></div>');
        
    });

     $("#add_input_color").on('click', function (e) {
        
       inputsColors.append('<div class="col-xs-3">'+
                           '<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>'+
                           '<input type="text" name="colors[]" class="form-control colorfield" /></div>');

        colPick($('.colorfield'));
        
    });

     function colPick (input) {
         
          var colorx;

         input.each(function(){ 
          
           colorx = ($(this).val() == "ffffff") ? "000000" : "ffffff";

           $(this).siblings('.delete').css('color', '#'+colorx);
           $(this).css('border-color','#'+ $(this).val());

         });
         
         
         input.colpick({
                layout:'hex',
                submit:0,
                colorScheme:'dark',
                onChange:function(hsb,hex,rgb,el,bySetColor) {
                    colorx = ($(el).val() == "ffffff") ? "000000" : "ffffff";
                    
                    $(el).siblings('.delete').css('color', '#'+colorx);
                    $(el).css('border-color','#'+hex);
                    // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                    if(!bySetColor) $(el).val(hex);
                }
            }).keyup(function(){
                $(this).colpickSetColor(this.value);
            });
     }

     colPick(colorfield);

    
        
       
    inputsSizes.on('click','.delete',function (e) {
       
        $(this).parent('div').remove();
    });
    inputsColors.on('click','.delete',function (e) {
       
        $(this).parent('div').remove();
    });


    function deletePhoto()
    {
        var btn_delete = $(this),
            url = "/store/admin/photos/" + btn_delete.attr("data-imagen");
        
        $.post(url,{_token: $('input[name=_token]').val()}, function(data){
            btn_delete.parent().fadeOut("slow");
        });
    }
    
    $("#UploadButton").ajaxUpload({
        url : "/store/admin/photos",
        name: "file",
        data: {id: $('input[name=product_id]').val(), _token: $('input[name=_token]').val() },
        onSubmit: function() {
            infoBox.html('Uploading ... ');
        },
        onComplete: function(result) {
            
           infoBox.html('Uploaded succesfull!');
            
            var photos = jQuery.parseJSON(result);
          
            
            fillPhotosInfo(photos);

            gallery.find('li').find('.delete').on('click',deletePhoto);
           

        }
    });

    gallery.find('li').find('.delete').on('click',deletePhoto);
    
    function photoTemplate(photo)
    {
  
          var templateHtml = $.trim( $('#photoTemplate').html() );
         
          var template = Handlebars.compile( templateHtml );

          return template(photo);
    
    }

  
    function fillPhotosInfo(jsonData) {
        if (jsonData.error) {
            return onError();
        }

        var html = photoTemplate(jsonData);
     
        (gallery.length === 0) ? gallery.html( html ) : gallery.prepend(html);
       
        gallery.find('li').eq(0).hide().show("slow");

    }

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

    // patner add modal user

    $('.patners').on('click', '.delete', function(e) {
        $(this).parent('li').remove();
        $('input[name="parent_id"]').val('');
    });

    $('#btn-add-patner').on('click', function(event) {
        event.preventDefault();
        getPatners(fillPatnersInfo);
    });

    $('#modalAddPatner').find('.btn-primary').on('click', function(event) {
        event.preventDefault();

        //var allVals = [];
        $('[name=chkPatners]:checked').each(function() {
            // allVals.push($(this).val());
            $('ul.patners').empty();
            for (var i = 0 ; i < patners.length; i++) {

                if($(this).val() == patners[i].id)
               {

                   $('input[name="parent_id"]').val(patners[i].id);
                   $('input[name="user_id_payment"]').val(patners[i].id);
                //    if (yaAgregado($(this).val()) == false)
                //    {
                        $('ul.patners').append('<li data-id="' + patners[i].id +'"><span class="delete" data-id="'+ patners[i].id +'"><i class="glyphicon glyphicon-remove"></i></span>'+
                        '<span class="label label-success">'+ patners[i].name +'</span></li>');
                      /* $('ul.patners').append('<li data-id="' + patners[i].id +'"><span class="delete" data-id="'+ patners[i].id +'"><i class="glyphicon glyphicon-remove"></i></span>'+
                       '<span class="label label-success">'+ patners[i].name +'</span>'+
                       '<input type="hidden" name="patner_id" value="'+ patners[i].id +'"></li>');*/
              //      }



                }
            };


        });

        $('#modalAddPatner').modal('hide');


    });

    $('.modal-dialog').find('.pagination').on('click','a', function(event) {
        event.preventDefault();
        getPatners(fillPatnersInfo,$(this).data('page'));
    });


    function yaAgregado(id){

        var res = false;

        $('.patners').children('li').each(function() {

            if($(this).data('id') == parseInt(id))
                res = true;

        });

        return res;

    }
    function Pagination (total, page, max_pages,items_per_page) {


        var len = total,// total de items
            page = page,// pagina actual
            pagesVisibles = max_pages,
            totalPages = Math.ceil(len / items_per_page),
            pageStart = (page % pagesVisibles === 0) ? (parseInt(page / pagesVisibles, 10) - 1) * pagesVisibles + 1 : parseInt(page / pagesVisibles, 10) * pagesVisibles + 1,//calculates the start page.
            output = [],
            i = 0,
            counter = 0;


        pageStart = pageStart < 1 ? 1 : pageStart;//check the range of the page start to see if its less than 1.

        for (i = pageStart, counter = 0; counter < pagesVisibles && i <= totalPages; i = i + 1, counter = counter + 1) {//fill the pages

            output.push(i);
        }

        output.first = 1;//add the first when the current page leaves the 1st page.

        if (page > 1) {// add the previous when the current page leaves the 1st page
            output.prev = page - 1;
        } else {
            output.prev = 1;
        }

        if (page < totalPages) {// add the next page when the current page doesn't reach the last page
            output.next = page + 1;
        } else {
            output.next = totalPages;
        }

        output.last = totalPages;// add the last page when the current page doesn't reach the last page

        output.current = page;//mark the current page.

        output.total = totalPages;

        output.numberOfPages = pagesVisibles;


        if(output.length>0)
        {

            buildItem(output);
        }




    }
    function buildItem (output){
        $('.pagination').html("");
        if (output.first) {//if the there is first page element


            var first = $('<li></li>',{
                class: 'first',
                html : "<a href='#"+ output.first +"' data-page='"+  output.first +"'> &lt;&lt;</a>"

            });


            if (first) {
                $('.pagination').append(first);

            }

        }

        if (output.prev) {//if the there is previous page element

            var prev = $('<li></li>',{
                class: 'prev',
                html : "<a href='#"+ output.prev +"' data-page='"+  output.prev +"'> &lt;</a>"

            });

            if (prev) {
                $('.pagination').append(prev);

            }

        }


        for (var i = 0; i < output.length; i = i + 1) {//fill the numeric pages.

            var p = $('<li></li>',{
                class: (output[i] === output.current) ?  "active" : "",
                html : "<a href='#"+ output[i] +"' data-page='"+ output[i] +"'>"+ output[i] +"</a>"

            });


            if (p) {
                $('.pagination').append(p);
            }
        }

        if (output.next) {//if there is next page

            var next = $('<li></li>',{
                class: 'next',
                html : "<a href='#"+ output.next +"' data-page='"+  output.next +"' > &gt;</a>"

            });

            if (next) {
                $('.pagination').append(next);
            }
        }

        if (output.last) {//if there is last page

            var last = $('<li></li>',{
                class: 'last',
                html : "<a href='#"+ output.last +"' data-page='"+ output.last +"'> &gt;&gt;</a>"

            });

            if (last) {
                $('.pagination').append(last);
            }
        }
    }

    function getPatners (callback, page) {
        var p = page ? parseInt(page, 10) : 1;
        $('.loading-search').removeClass('hidden');
        $.ajax({

            url : '/store/admin/users/list',
            dataType : 'json',
            data : { exc_id: $('input[name="user_id"]').val(), page: p }

        }).done(callback);
    }

    function PatnerTemplate(patners)
    {

        var templateHtml = $.trim( $('#patnersTemplate').html() );

        var template = Handlebars.compile( templateHtml );

        return template(patners);

    }


    function fillPatnersInfo(jsonData) {
        if (jsonData.error) {
            return onError();
        }
        $('.loading-search').addClass('hidden');
        patners = $.map(jsonData.data ,function(obj, index){
            return {
                id : obj.id,
                name : obj.username


            }

        });

        var html = PatnerTemplate(patners);

        $('#modalAddPatner').find('.tbody').html( html );

        Pagination(jsonData.total, jsonData.current_page, 10,jsonData.per_page);

    }

    $('#searchText').on('keypress', function(event) {

        if (event.keyCode == 13) {
            event.preventDefault();
        }

    });
    $('#searchText').on('keyup', function(event) {
        search();
    });
    function search() {

        var input = $('#searchText'),
            key =input.val(),
            self = this;

        if(key.length >=3 ){

            $('.loading-search').removeClass('hidden');
            clearTimeout( this.timer );
            this.timer = setTimeout(function () {
                console.log('search ' + key);
                getPatnersByName(fillPatnersInfo,key);

            },200);


        }else if(key.length == 0){
            $('.dropdown').removeClass('open');
            getPatners(fillPatnersInfo);
        }




    }
    function getPatnersByName (callback, key) {

        $.ajax({

            url : '/store/admin/users/list',
            dataType : 'json',
            data : { exc_id: $('input[name=user_id]').val(), key: key }

        }).done(callback);
    }

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




});