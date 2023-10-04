<?php

namespace UserPackage\App\Http\Controllers;

use UserPackage\App\Models\User;
use Illuminate\Http\Request;
use UserPackage\App\Models\ClientProfile;
use Illuminate\Support\Facades\Hash;

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

    public function index()
    {
        // $user = User::find(1);

        // $user->users()->where('profile_id', $client->id)->get();
        return ClientProfile::find(1);
    }

    public function store(Request $request, ClientProfile $client)
    {

        $this->validate($request, [
            'username' => ['required_if:email, null', 'required_if:mobile_number, null'],
            'email' => ['nullable', 'email', 'unique:admin_profiles'],
            'mobile_number' => ['nullable'],
            'password' => ['required']
        ]);

        $client = ClientProfile::create([
            'username' => $request->username,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number
        ]);

        $client->profile()->create([
            'password' => Hash::make($client->password)
        ]);

        return array(
            "id" => $client->id,
            "attributes" => [
                'username' => $client->username,
                'email' => $client->email,
                'mobile_number' => $client->mobile_number,
                'created_at' => $client->created_at,
                'updated_at' => $client->updated_at,
            ],
        );
    }
}
