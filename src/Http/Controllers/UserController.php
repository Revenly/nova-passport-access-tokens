<?php
namespace  R64\NovaPassportAccessTokens\Http\Controllers;

use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function list()
    {
        $users = \App\Models\User::query()
            ->select(['email', 'first_name', 'last_name'])
            ->get();

        return response()->json(compact('users'), 200);
    }
}
