<?php
namespace  R64\NovaPassportAccessTokens\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function list()
    {
        return response()->json(['users' => User::getForNova()->all()], 200);
    }
}
