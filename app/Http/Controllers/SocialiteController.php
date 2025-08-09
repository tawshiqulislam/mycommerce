<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SocialiteController extends Controller
{
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('google_id', $googleUser->id)->first();
        if ($user) {
            Auth::login($user);
            return redirect()->route('home');
        } else {
            return inertia('Auth/GoogleUserForm', [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
            ]);
        }
    }

    public function storeGoogleUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'google_id' => 'required|string|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'referrer_code' => 'nullable|string|max:255',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'google_id' => $request->google_id,
            'phone' => $request->phone,
            'referrer_code' => $request->referrer_code,
            'referral_code' => $this->generateUniqueReferralCode(),
        ]);
        $user->assignRole('client');
        Auth::login($user);
        return redirect()->route('home');
    }

    private function generateUniqueReferralCode()
    {
        do {
            $referralCode = 'me' . rand(10000, 99999);
        } while (DB::table('users')->where('referral_code', $referralCode)->exists());
        return $referralCode;
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::updateOrCreate([
                    'name' => $user->name,
                    'facebook_id'=> $user->id,
                    'referral_code' => $this->generateUniqueReferralCode(), 
                ]);

                Auth::login($newUser);
                return redirect()->route('home');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to login with Facebook: ' . $e->getMessage());
        }
    }
}
