<?php namespace App\Http\Controllers;


use App\Category;
use App\Http\Requests\ContactRequest;
use App\Repositories\CategoryRepository;
use App\Mailers\ContactMailer;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class PagesController extends Controller {


    /**
     * @var App\Mailers\ContactMailer
     */
    private $mailer;

    /**
     * @var ProductRepository
     */
    private $productRepository;


    function __construct(ContactMailer $mailer, ProductRepository $productRepository)
    {

        $this->mailer = $mailer;
        $this->productRepository = $productRepository;





    }


    /**
     * Display a home page.
     * GET /pages
     *
     * @return Response
     */
    public function index()
    {
        $products = $this->productRepository->getFeatured();

        return View::make('pages.index')->withProducts($products);
    }

    /**
     * Display about page
     *
     * @return Response
     */
    public function about()
    {
        return View::make('pages.about');
    }

    /**
     * Display oportunity page
     *
     * @return Response
     */
    public function opportunity()
    {
        return View::make('pages.opportunity');
    }

    /**
     * Page Plan de ayuda
     * @return mixed
     */
    public function  aid()
    {
        return View::make('pages.aid_plan');
    }

    /**
     * Page terms & conditions
     * @return mixed
     */
    public function  terms()
    {
        return View::make('pages.terms');
    }


    /**
     * Page Contact us
     * @return mixed
     */
    public function contact()
    {
        return View::make('pages.contact');

    }

    /**
     * Page Contact us Post
     * @return mixed
     */
    public function postContact(ContactRequest $request)
    {
        $data = $request->all();

        $this->mailer->contact($data);

        Flash::message('Mensaje enviado correctamente');

        return Redirect::route('contact');
    }


}