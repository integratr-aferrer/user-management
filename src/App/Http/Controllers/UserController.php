<?php

namespace UserPackage\App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => ['required_if:email,null', 'required_if:mobile_number,null'],
            'email' => ['nullable', 'email'],
            'mobile_number' => ['nullable'],
            'password' => ['required'],
        ]);
    }
}
