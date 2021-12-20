<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'token_name' => ['required', 'string', 'max:50']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();

        if (Auth::attempt($validator->safe()->only('email', 'password'))) {
            $user = User::where('email', $validated['email'])->firstOrFail();
            $newAccessToken = $user->createToken($validated['token_name']);
            $id = $newAccessToken->accessToken->id;
            $token = $newAccessToken->plainTextToken;

            return response()->json(
                [
                    'token_id' => $id,
                    'token_name' => $validated['token_name'],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ],
                201
            );
        }

        return response()->json(['message' => 'Credenciais de acesso incorretas'], 401);
    }

    public function show(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'token_id' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();

        if (Auth::attempt($validator->safe()->only('email', 'password'))) {
            Auth::user()->tokens()->where('id', $validated['token_id'])->delete();

            return response()->json(['message' => 'Token ' . $validated['token_id'] . ' deletado'], 200);
        }

        return response()->json(['message' => 'Credenciais de acesso incorretas'], 401);
    }
}
