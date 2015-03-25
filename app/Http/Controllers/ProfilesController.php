<?php namespace App\Http\Controllers;


use App\Http\Requests\ProfileRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class ProfilesController extends Controller {


    protected $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;


        $this->middleware('currentUser', ['only' => ['edit', 'update']]);
    }

    /**
     * Display the specified resource.
     * GET /profiles/{id}
     *
     * @param $username
     * @return Response
     */
    public function show($username)
    {
        $user = $this->userRepository->findByUsername($username);

        return View::make('profiles.show')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /profiles/{id}/edit
     *
     * @param $username
     * @internal param int $id
     * @return Response
     */
    public function edit($username)
    {
        $user = $this->userRepository->findByUsername($username);

        return View::make('profiles.edit')->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     * PUT /profiles/{id}
     *
     * @param $username
     * @param ProfileRequest $request
     * @return Response
     * @internal param int $id
     */
    public function update($username, ProfileRequest $request)
    {
        $user = $this->userRepository->findByUsername($username);
        $input = $request->all();
        $user->profiles->fill($input)->save();

        Flash::message('Perfil Actualizado!');

        return Redirect::route('profile.edit', $user->username);
    }


}