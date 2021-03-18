<?php
namespace  R64\NovaPassportAccessTokens\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class TokenController
{
    protected $tokenRepository;

    protected $validation;

    public function __construct(TokenRepository $tokenRepository, ValidationFactory $validation)
    {
        $this->tokenRepository = $tokenRepository;

        $this->validation = $validation;
    }

    public function create(Request $request)
    {
        $this->validation->make($request->all(), [
            'user' => 'required|exists:users,email',
            'name' => 'required',
            'scopes' => 'required'
        ])->validate();

        $user = \App\Models\User::where('email', '=', $request->get('user'))->first();

        $name = $request->get('name');
        $scopes = $request->get('scopes');

        $token  = $user->createToken($name, $scopes);

        return response()->json(compact('token'), JsonResponse::HTTP_CREATED);
    }

    public function list()
    {
        $tokens =  Passport::token()->forNova()->get();

        return $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && ! $token->revoked;
        })->values();
    }

    /**
     * Delete the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $tokenId)
    {
        $token = $this->tokenRepository->find($tokenId);

        if (is_null($token)) {
            return new Response('', 404);
        }

        $token->revoke();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
