<?php namespace App\Http\Controllers;



use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;

class SessionsController extends Controller {

    function __construct()
    {
        $this->middleware('guest',['only' => ['create']]);
    }


    /**
     * Show the form for creating a new resource.
     * GET /sessions/create
     *
     * @return Response
     */
    public function create()
    {
        return View::make('sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /sessions
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function store(LoginRequest $request)
    {
        $input = $request->only('email', 'password');
        $input = array_add($input, 'active', '1');

        if (Auth::attempt($input))
        {
            return Redirect::intended('/');
        }

        Flash::error('Credenciales Invalidas');

        return Redirect::back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /sessions/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id = null)
    {
        Auth::logout();

        return Redirect::home();
    }

}