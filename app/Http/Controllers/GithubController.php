<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user = User::where('github_id', $githubUser->id)->first();

            if ($user) {
                Auth::login($user);
            } else {
                $user = User::updateOrCreate(
                    ['email' => $githubUser->email],
                    [
                        'name' => $githubUser->name ?? $githubUser->nickname,
                        'github_id' => $githubUser->id,
                        'password' => Hash::make(Str::random(32)),
                    ]
                );

                Auth::login($user);
            }

            return redirect()->intended('dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
