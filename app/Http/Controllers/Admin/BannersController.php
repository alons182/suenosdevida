<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\View;
use App\UploadHandler as Upload;

class BannersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $path = dir_banners_path();

        File::exists($path) or File::makeDirectory($path);
        $files =File::files($path);
        $filesArray = [];

        foreach ($files as $file)
        {
            $fileArray = array(
                'type' => File::extension($file),
                'size' => File::size($file),
                'name'  => explode("//",$file)[1]

            );
            $filesArray[] = $fileArray;
        }


        return View::make('admin.banners.index')->withFiles(new Collection($filesArray));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
	public function store(Request $request)
	{

        $file = $request->file('file');

        $upload = new Upload(array(
            'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/banners_files/',
            'upload_url' => url('/').'/banners_files/'
        ));


        try {
           // $upload->process($file);
        } catch(Exception $exception){
            // Something went wrong. Log it.
            Log::error($exception);
            $error = array(
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'error' => $exception->getMessage(),
            );
            // Return error
            return Response::json($error, 400);
        }

        // If it now has an id, it should have been successful.
        /*if ( $upload->id ) {
            $newurl = URL::asset($upload->publicpath().$upload->filename);

            // this creates the response structure for jquery file upload
            $success = new stdClass();
            $success->name = $upload->filename;
            $success->size = $upload->size;
            $success->url = $newurl;
            $success->thumbnailUrl = $newurl;
            $success->deleteUrl = action('UploadController@delete', $upload->id);
            $success->deleteType = 'DELETE';
            $success->fileID = $upload->id;

            return Response::json(array( 'files'=> array($success)), 200);
        } else {
            return Response::json('Error', 400);
        }*/
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $path = dir_banners_path();

        if(File::exists($path.$id))
            File::delete(dir_banners_path() . $id);

        if(File::exists($path.'/thumbnail/'.$id))
            File::delete(dir_banners_path() .'/thumbnail/'.$id);



	}
    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }


}
