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
            'email' => 'required|email:rfc,dns',
        ]);

        // Verify the domain has valid MX records (can actually receive mail)
        $domain = substr(strrchr($request->email, '@'), 1);
        if (! $this->hasMxRecord($domain)) {
            return back()
                ->withErrors(['email' => 'That email address does not appear to be deliverable. Please check and try again.'])
                ->withInput();
        }

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

    /**
     * Check whether the given domain has at least one MX record,
     * meaning it is configured to receive email.
     */
    private function hasMxRecord(string $domain): bool
    {
        // checkdnsrr returns true if any MX record exists for the domain.
        // Fall back to an A/AAAA record check, since some small domains
        // receive mail directly without a dedicated MX entry.
        return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A') || checkdnsrr($domain, 'AAAA');
    }
}

