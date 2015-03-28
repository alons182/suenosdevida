<?php namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Mailers\UserMailer;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class RegistrationController extends Controller {


    protected $userRepository;
    /**
     * @var UserMailer
     */
    private $mailer;

    function __construct(UserRepository $userRepository, UserMailer $mailer)
    {

        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }


    /**
     * Show the form for creating a new resource.
     * GET /registration/create
     *
     * @param null $username
     * @return Response
     */
    public function create($username = null)
    {
        $user = ($username) ? $this->userRepository->findByUsername($username) : null;

        return View::make('registration.create')->withParent_user($user);
    }

    /**
     * Store a newly created resource in storage.
     * POST /registration
     *
     * @param RegistrationRequest $request
     * @return Response
     */
    public function store(RegistrationRequest $request)
    {
        $input = $request->only('username', 'email','email_confirmation', 'password', 'password_confirmation', 'parent_id', 'terms');

        $user = $this->userRepository->store($input);


            Auth::login($user);

            Flash::message('Usuario creado. se te ha enviado un correo con la información de usuario y una url para que la compartas con otros usuarios que quieras para que se agreguen a tu red. Completa tu perfil por favor, es importante !');

            $this->mailer->sendWelcomeMessageTo($user,$input['password']);


            return Redirect::route('profile.edit', $user->username);

    }


}