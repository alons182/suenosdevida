@extends('admin.layouts.layout')

@section('content') 
    <h1>Banners</h1>
<!-- The file upload form used as target for the file upload widget -->
    {!! Form::open(['route'=>'banners_store_path','files'=> true, 'id'=>'fileuploadBanners']) !!}
        <input type="hidden" name="folder" value="banners" id="folder">
    <!--<form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">-->
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
               <input class="btn btn-success file-inputs" title="Add files..." type="file" name="files[]" multiple>
               <!-- <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>

                    <input class="file-inputs" title="Add files..." type="file" name="files[]" multiple>
                </span>-->
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped">
            <tbody class="files">
                 @foreach ($files as $file)
                <tr class="template-download ">
                        <td>
                            <span class="preview">

                                    <a href="/banners_files/{!! $file['name'] !!}" title="{!! $file['name'] !!}" download="{!! $file['name'] !!}" data-gallery>
                                    @if($file['type'] == 'jpg' || $file['type'] == 'jpeg' || $file['type'] == 'png' || $file['type'] == 'gif' )
                                        <img src="/banners_files/thumbnail/{!! $file['name'] !!}">
                                    @else
                                        <img src="/js/holder.js/80x49" alt="No Image">
                                    @endif
                                    </a>

                            </span>
                        </td>
                        <td>
                            <p class="name">

                                    <a href="/banners_files/{!! $file['name'] !!}" title="{!! $file['name'] !!}">{!! $file['name'] !!}</a>



                            </p>

                        </td>
                        <td>
                            <span class="size">{!! number_format($file['size']/1024,2) !!} KB</span>
                        </td>
                        <td>


                                <button class="btn btn-danger delete" data-type="DELETE" form="form-delete" data-url="/store/admin/banners/{!! $file['name'] !!}">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Delete</span>
                                </button>
                                <input type="checkbox" name="delete" value="1" class="toggle">

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}"><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" >{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}


                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="/store/admin/banners/{%=file.name%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>


@stop
@section('scripts')

<script src="/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/js/vendor/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/js/vendor/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="/js/vendor/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="/js/vendor/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="/js/vendor/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="/js/vendor/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="/js/vendor/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="/js/vendor/jquery.fileupload-ui.js"></script>
<script src="/js/vendor/bootstrap.file-input.js"></script>

<!-- The main application script -->
<script src="/js/vendor/upload.js"></script>
@stop