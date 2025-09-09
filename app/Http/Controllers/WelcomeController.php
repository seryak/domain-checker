<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Native\Laravel\Facades\Settings;

class WelcomeController extends Controller
{
    /**
     * Show the welcome screen with terms agreement
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        $termsAccepted = Settings::get('terms_accepted', false);

        if ($termsAccepted) {
            return redirect()->route('analytics.consent');
        }

        return view('welcome');
    }

    /**
     * Accept terms and conditions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acceptTerms(Request $request)
    {
        // Save terms acceptance
        Settings::set('terms_accepted_at', now()->toISOString());
        Settings::set('terms_accepted', true);

        // Track event in PostHog (if enabled)
        if (Settings::get('anonymous_statistics', false)) {
            $this->trackPostHogEvent('terms_accepted', [
                'timestamp' => now()->toISOString()
            ]);
        }

        return redirect()->route('analytics.consent');
    }

    /**
     * Show the analytics consent screen
     *
     * @return \Illuminate\Http\Response
     */
    public function analyticsConsent()
    {
        // Ensure terms are accepted before showing analytics consent
        $termsAccepted = Settings::get('terms_accepted', false);

        if (!$termsAccepted) {
            return redirect()->route('welcome');
        }

        $consentGiven = Settings::get('analytics_consent_given', false);
        $consentTimestamp = Settings::get('analytics_consent_timestamp', null);

        return view('analytics-consent', compact('consentGiven', 'consentTimestamp'));
    }

    /**
     * Handle analytics consent submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitAnalyticsConsent(Request $request)
    {
        $request->validate([
            'consent' => 'required|in:allow,decline'
        ]);

        $consent = $request->consent === 'allow';

        // Save consent
        Settings::set('anonymous_statistics', $consent);
        Settings::set('analytics_consent_given', true);
        Settings::set('analytics_consent_timestamp', now()->toISOString());

        // Mark first launch as completed
        Settings::set('first_launch_completed', true);

        // Track the consent decision if consent was given for analytics
        if ($consent) {
            $this->trackPostHogEvent('analytics_consent_given', [
                'consent' => $consent,
                'timestamp' => now()->toISOString()
            ]);
        }

        return redirect()->route('ssl.report');
    }

    /**
     * Track event with PostHog if available
     *
     * @param string $eventName
     * @param array $properties
     * @return void
     */
    private function trackPostHogEvent(string $eventName, array $properties = [])
    {
        // PostHog integration is handled in the layout
        // This ensures the event is tracked only if PostHog is enabled
        if (Settings::get('anonymous_statistics', false) && function_exists('posthog')) {
            // Events will be tracked via JavaScript in the layout
        }
    }
}