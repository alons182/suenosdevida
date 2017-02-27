<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Mailers\ContactMailer;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use Swift_RfcComplianceException;

class CataloguesController extends Controller {


    /**
     * @var CategoryRepository
     */
    private $catalogueRepository;
    /**
     * @var CategoryForm
     */
    //private $categoryForm;

    function __construct(ShopRepository $shopRepository, ContactMailer $mailer)
    {
       
        $this->shopRepository = $shopRepository;
        $this->mailer = $mailer;
        //$this->middleware('authByRoleAdmins');
    }


    /**
     * Display a listing of the resource.
     * GET /catalogues
     *
     * @return Response
     */
    public function request($shop_id, Request $request)
    {
       $this->validate($request, [
                'email' => 'required|email',
                'comment' => 'required',
            ]);

        $data = $request->all();

        $shop = $this->shopRepository->findById($shop_id);

        $data['shop_email'] = ($shop->responsable) ? $shop->responsable->email : 'info@suenosdevidacr.com';

         try {
                 $this->mailer->catalogue($data);

                  Flash::message('Solicitud enviada correctamente');

            }catch (Swift_RfcComplianceException $e)
            {
                Log::error($e->getMessage());
            }
       


       

        return Redirect::back();
    }

    
    

}