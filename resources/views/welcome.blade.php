@extends('layouts.app')

@section('title', __('welcome.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-4xl font-bold text-primary mb-2">{{ __('welcome.title') }}</h1>
            <p class="text-lg text-base-content/70">
                {{ __('welcome.agreement_text') }}

                <a href="#terms-modal" class="text-primary hover:text-primary-focus underline font-medium">
                    {{ __('welcome.terms_link') }}
                </a>

                &nbsp;{{ __('welcome.privacy_connector') }}

                <a href="#privacy-modal" class="text-primary hover:text-primary-focus underline font-medium">
                    {{ __('welcome.privacy_link') }}
                </a>
            </p>
        </div>

        <!-- Continue Button -->
        <div class="flex justify-center">
            <form action="{{ route('welcome.accept') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-focus text-primary-content font-medium py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-focus focus:ring-offset-2">
                    {{ __('welcome.button_continue') }}
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-base-content/50">
            SSLPatrol v{{ config('nativephp.appVersion', '1.0') }}
        </div>
    </div>

    <!-- Terms Modal -->
    <div id="terms-modal" class="modal">
        <div class="modal-box w-11/12 max-w-4xl max-h-[80vh] overflow-y-auto">
            <h3 class="font-bold text-lg mb-4">{{ __('welcome.terms_link') }}</h3>
            <div class="prose prose-sm max-w-none">
                <h1>Terms of Service</h1>

                <p><strong>Effective date:</strong> [set date]</p>

                <p>These Terms of Service ("Terms") govern your use of the SSLPatrol desktop application ("Application"), owned and operated by <strong>Mikhail Seriakov</strong> ("we", "us", or "our"). By downloading, installing, or using the Application, you ("you", "user") agree to be bound by these Terms.</p>

                <h2>1. Use of the Application</h2>
                <ul>
                    <li>The Application is currently provided free of charge.</li>
                    <li>You may install and use the Application on any number of devices.</li>
                    <li>You agree not to:</li>
                    <ul>
                        <li>Resell, rent, or sublicense the Application.</li>
                        <li>Use the Application for sending spam, conducting attacks, or other malicious activities.</li>
                    </ul>
                </ul>

                <h2>2. Data Collection and Privacy</h2>
                <ul>
                    <li>The Application collects limited usage statistics via <strong>PostHog</strong> to help improve functionality.</li>
                    <li>In the future, cloud integration may be introduced (e.g., storing domain data on our servers). You will be informed when this feature becomes available.</li>
                    <li>We may collect your email address with your explicit consent for the purpose of sending product updates and news.</li>
                    <li>For details, please refer to our Privacy Policy.</li>
                </ul>

                <h2>3. Disclaimer of Warranties</h2>
                <ul>
                    <li>The Application is provided <strong>"as is"</strong>, without any warranties, express or implied.</li>
                    <li>We do not warrant that the Application will be error-free, uninterrupted, or meet your specific requirements.</li>
                </ul>

                <h2>4. Limitation of Liability</h2>
                <ul>
                    <li>We are not responsible for any damages, data loss, or consequences resulting from the use of the Application.</li>
                    <li>You assume full responsibility for your use of the Application.</li>
                </ul>

                <h2>5. Termination</h2>
                <p>We may suspend or terminate your access to the Application if you violate these Terms. You may stop using the Application at any time by uninstalling it.</p>

                <h2>6. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of the <strong>Republic of Serbia</strong>.</p>

                <h2>7. Changes to the Terms</h2>
                <p>We may update these Terms from time to time. The most current version will always be available at <a href="https://sslpatrol.io" target="_blank">sslpatrol.io</a>. By continuing to use the Application after updates, you agree to the revised Terms.</p>

                <h2>8. Language of the Terms</h2>
                <p>This document may be available in other languages for convenience. In case of any conflict or inconsistency, the <strong>English version shall prevail</strong>.</p>

                <h2>9. Contact</h2>
                <p>For questions about these Terms, please contact us at:</p>
                <p><strong>Email:</strong> hello@sslpatrol.io<br>
                <strong>Website:</strong> <a href="https://sslpatrol.io" target="_blank">https://sslpatrol.io</a></p>
            </div>
            <div class="modal-action">
                <a href="#" class="btn">Закрыть</a>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div id="privacy-modal" class="modal">
        <div class="modal-box w-11/12 max-w-4xl max-h-[80vh] overflow-y-auto">
            <h3 class="font-bold text-lg mb-4">{{ __('welcome.privacy_link') }}</h3>
            <div class="prose prose-sm max-w-none">
                <h1>Privacy Policy</h1>

                <p><strong>Effective date:</strong> [set date]</p>

                <p>This Privacy Policy explains how <strong>SSLPatrol</strong> ("we", "us", or "our"), operated by <strong>Mikhail Seriakov</strong>, collects, uses, and protects your information when you use our desktop application ("Application").</p>

                <h2>1. Information We Collect</h2>
                <p>We may collect the following types of information:</p>
                <ul>
                    <li><strong>Usage Data</strong>: We use <strong>PostHog</strong> analytics to collect anonymized information about how you use the Application (e.g., number of checks, feature usage, application performance). This data does not include personal content such as your domains or SSL certificates.</li>
                    <li><strong>Email Address</strong>: If you provide your email with explicit consent, we may store it to send you updates, product news, and information about new features.</li>
                    <li><strong>Future Features</strong>: If cloud integration is introduced (e.g., storing domain data on our servers), additional information may be collected. You will be informed and asked for consent before this feature becomes active.</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>We use collected information to:</p>
                <ul>
                    <li>Improve the Application and user experience.</li>
                    <li>Monitor performance and detect issues.</li>
                    <li>Communicate with you about updates and new features (only if you have given consent).</li>
                </ul>

                <h2>3. Sharing of Information</h2>
                <ul>
                    <li>We do not sell or rent your data to third parties.</li>
                    <li>Usage analytics are processed by <strong>PostHog</strong>, an analytics provider. Data may be transferred to their servers depending on your location.</li>
                    <li>We may disclose information if required by law or to protect our rights and security.</li>
                </ul>

                <h2>4. Data Retention</h2>
                <ul>
                    <li>Usage data is stored for as long as necessary to improve the Application.</li>
                    <li>Email addresses are stored until you unsubscribe or request deletion.</li>
                    <li>If future cloud storage is implemented, retention rules will be clearly communicated.</li>
                </ul>

                <h2>5. Your Rights (GDPR / EU Users)</h2>
                <p>If you are located in the EU or EEA, you have the right to:</p>
                <ul>
                    <li>Access, correct, or delete your personal data.</li>
                    <li>Withdraw consent at any time (e.g., unsubscribe from emails).</li>
                    <li>Request a copy of the data we hold about you.</li>
                </ul>
                <p>You can exercise these rights by contacting us at <strong>hello@sslpatrol.io</strong>.</p>

                <h2>6. Security</h2>
                <p>We take reasonable measures to protect your information, but no system is 100% secure. You use the Application at your own risk.</p>

                <h2>7. Children's Privacy</h2>
                <p>The Application is not directed to children under the age of 16, and we do not knowingly collect data from them.</p>

                <h2>8. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. The latest version will always be available at <a href="https://sslpatrol.io" target="_blank">sslpatrol.io</a>.</p>

                <h2>9. Language of the Policy</h2>
                <p>This document may be available in other languages for convenience. In case of any conflict or inconsistency, the <strong>English version shall prevail</strong>.</p>

                <h2>10. Contact</h2>
                <p>If you have questions about this Privacy Policy, please contact us:</p>
                <p><strong>Email:</strong> hello@sslpatrol.io<br>
                <strong>Website:</strong> <a href="https://sslpatrol.io" target="_blank">https://sslpatrol.io</a></p>
            </div>
            <div class="modal-action">
                <a href="#" class="btn">Закрыть</a>
            </div>
        </div>
    </div>
</div>
@endsection
