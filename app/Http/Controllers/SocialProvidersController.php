<?php

namespace App\Http\Controllers;

use App\Events\DefineLoginEvent;
use App\Services\Interfaces\Social;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialProvidersController extends Controller
{
    public function redirect(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver, Social $social)
    {
        try {
            $socialUser = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $user = $social->findOrCreateUser($socialUser);

        Auth::login($user, true);
        event(new DefineLoginEvent($user));

        return redirect(route('home'));
    }
}
