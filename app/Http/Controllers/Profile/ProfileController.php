<?php

namespace App\Http\Controllers\Profile;


use App\Models\User;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(): Response
    {
        $order_in_progress = Order::where('user_id', Auth::user()->id)->whereIn('status', ['pending', 'shipped'])->count();
        $total_order = Order::where('user_id', Auth::user()->id)->count();
        $total_purchase = Order::where('user_id', Auth::user()->id)->sum('sub_total');
        return Inertia::render('Profile/Dashboard', [
            'order_in_progress' => Order::where('user_id', Auth::id())->whereIn('status', ['pending', 'shipped'])->count(),
            'total_order' => Order::where('user_id', Auth::id())->count(),
            'total_purchase' => Order::where('user_id', Auth::id())->sum('sub_total'),
        ]);
    }

    public function accountDetails(): Response
    {
        return Inertia::render('Profile/AccountDetails');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validate user input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
        ]);

        $user = Auth::user();

        if ($user) {
            // Format phone number
            $phoneNumber = $request->phone;
            if (strlen($phoneNumber) == 11) {
                $phoneNumber = '88' . $phoneNumber;
            } elseif (strlen($phoneNumber) == 10) {
                $phoneNumber = '880' . $phoneNumber;
            }

            // Check for unique phone number (excluding current user)
            if (User::where('id', '!=', $user->id)->where('phone', $phoneNumber)->exists()) {
                return Redirect::route('profile.account-details')->with('error', 'Phone number already exists');
            }

            // Check for unique email (excluding current user)
            if ($request->email && User::where('id', '!=', $user->id)->where('email', $request->email)->exists()) {
                return Redirect::route('profile.account-details')->with('error', 'Email already exists');
            }

            // Update user details
            $user->name = $request->name;
            $user->phone = $phoneNumber;
            $user->email = $request->email;
            $user->address = $request->address;

            // If email is changed, reset email verification
            if ($request->email && $request->email !== $user->getOriginal('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
        }

        return Redirect::route('profile.account-details')->with('success', 'Successfully updated');
    }


    // public function changePassword(): Response
    // {
    //     return Inertia::render('Profile/ChangePassword');
    // }

    // public function passwordUpdate(Request $request): RedirectResponse
    // {
    //     $validated = $request->validate([
    //         'current_password' => ['required', 'current_password'],
    //         'password' => ['required', Password::defaults(), 'confirmed'],
    //     ]);
    //     $request->user()->update([
    //         'password' => Hash::make($validated['password']),
    //     ]);
    //     return Redirect::route('profile.password')->with('success', 'Successfully updated');
    // }
}
