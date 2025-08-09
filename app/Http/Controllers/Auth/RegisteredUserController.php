<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        
        $validatedData = $request->validate([
            'phone' => 'required|string|unique:' . User::class,
            'otp' => 'required|numeric|digits:4',
            'referrer_code' => 'nullable|exists:users,referral_code',
        ], [
            'phone.unique' => 'There is already an account using this number.',
        ]);
        // ===
        // if(strlen($validatedData['phone']) == 11) {
        //     $validatedData['phone'] = '88' . $validatedData['phone'];
        // }
        // $validator = Validator::make(
        //     ['phone' => $validatedData['phone']],
        //     ['phone' => 'unique:users,phone']
        // );
    
        // if ($validator->fails()) {
        //     throw ValidationException::withMessages([
        //         'phone' => 'There is already an account using this number.',
        //     ]);
        // }
        // ===
        // dd($request->all(), $validatedData['phone']);
        $otp = Session::get('otp');
        if (!$otp || $otp !== $request->otp) {
            throw ValidationException::withMessages([
                'otp' => 'The OTP is incorrect.',
            ]);
        }
        $user = User::create([
            'name' => $request->phone,
            'phone' => $validatedData['phone'],
            'referral_code' => $this->generateUniqueReferralCode(),
            'referrer_code' => $request->input('referrer_code'),
        ]);
        $user->assignRole('client');
        event(new Registered($user));
        Auth::login($user);
        Session::forget('otp');
        return redirect(route('home'));
    }

    /**
     * Generate unique referral code.
     */
    private function generateUniqueReferralCode()
    {
        do {
            $referralCode = 'me' . rand(10000, 99999);
        } while (DB::table('users')->where('referral_code', $referralCode)->exists());
        return $referralCode;
    }
}
