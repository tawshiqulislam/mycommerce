<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function review()
    {
        $user = User::find(Auth::user()->id);
        $review = Review::where('user_id', $user->id)->first();
        $reviewData = $review ? $review->toArray() : null;
        return Inertia::render('Profile/Review', [
            'review' => $reviewData,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect()->route('profile.review')
                ->withErrors($validator)
                ->withInput();
        }
        $review = Review::findOrFail($id);
        $review->update($request->all());
        $review->save();
        return redirect()->route('profile.review')->with('success', 'Review updated successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect()->route('profile.review')
                ->withErrors($validator)
                ->withInput();
        }
        $review = new Review();
        $review->fill($request->all());
        $review->user_id = Auth::user()->id;
        $review->save();
        return redirect()->route('profile.review')->with('success', 'Thank you for the review!');
    }
}
