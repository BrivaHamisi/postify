<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:20' ],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required','min:6', 'max:30']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        // $user = User::create($incomingFields);
        // Auth::login($user);

        try {
            $user = User::create($incomingFields);
            Auth::login($user);

            $response = [
                'status' => 'success',
                'message' => 'User registered successfully',
                'user' => $user
            ];

            // Flash the response data to the session
            $request->session()->flash('response', $response);

            return redirect('/');

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);

               // Flash the response data to the session
               $request->session()->flash('response', $response);

               return redirect('/register');
        }

        // return 'Hello from User Register Controller';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginname' => ['required'],
            'loginpassword' => ['required']
        ]);

        if (Auth::attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])) {
            // Authentication passed...
            $request->session()->regenerate();
            
            $response = [
                'status' => 'success',
                'message' => 'User logged in successfully',
                'user' => Auth::user()
            ];

            // Flash the response data to the session
            $request->session()->flash('response', $response);

            return redirect('/');
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Login failed'
        ], 401);
    }
}
