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

        // If subscriber already exists
        $subscriber = Subscriber::where('email', $request->email)->first();

        if ($subscriber) {
            // If they had previously unsubscribed, resubscribe them
            if ($subscriber->unsubscribed_at) {
                $subscriber->update(['unsubscribed_at' => null]);
                
                // Send confirmation email again
                try {
                    Mail::to($request->email)->send(new SubscriptionConfirmed($subscriber));
                } catch (\Exception $e) {
                    logger()->error('Resubscription email failed: ' . $e->getMessage());
                }

                return back()->with('subscribed', 'Welcome back! You have fully resubscribed.');
            }

            // Otherwise, just tell them they are already subscribed
            return back()->with('subscribed', 'You are already subscribed!');
        }

        $subscriber = Subscriber::create(['email' => $request->email]);

        // Send confirmation email via SMTP
        try {
            Mail::to($request->email)->send(new SubscriptionConfirmed($subscriber));
        } catch (\Exception $e) {
            // Log the failure but don't break the subscription flow
            logger()->error('Subscription email failed: ' . $e->getMessage());
        }

        return back()->with('subscribed', 'You have successfully subscribed!');
    }

    /**
     * Unsubscribe the user from the newsletter.
     * This route should be signed to prevent unauthorized unsubscriptions.
     */
    public function destroy(Request $request, Subscriber $subscriber)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired unsubscribe link.');
        }

        $subscriber->update(['unsubscribed_at' => now()]);

        return view('blog.unsubscribed', ['email' => $subscriber->email]);
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

