<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function newsletter_old(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        return Redirect::back()->with('success', 'Subscribed to newsletter');
    }

    public function newsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        NewsletterSubscription::create([
            'email' => $request->email,
        ]);

        // return response()->json([
        //     'message' => 'Thank you for subscribing!',
        // ], 200);
        return Redirect::back()->with('success', 'Thank you for subscribing!');
    }
}
