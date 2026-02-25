<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmed;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // If subscriber already exists, return success without doing anything
        if (Subscriber::where('email', $request->email)->exists()) {
            return back()->with('subscribed', 'You have successfully subscribed!');
        }

        Subscriber::create(['email' => $request->email]);

        // Send confirmation email via SMTP
        try {
            Mail::to($request->email)->send(new SubscriptionConfirmed($request->email));
        } catch (\Exception $e) {
            // Log the failure but don't break the subscription flow
            logger()->error('Subscription email failed: ' . $e->getMessage());
        }

        return back()->with('subscribed', 'You have successfully subscribed!');
    }
}

