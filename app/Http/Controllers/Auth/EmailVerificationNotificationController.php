<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\ConfigurationService;
use App\Services\Core\HelperService;
use App\Services\EmailService\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    private EmailService $emailService;
    private ConfigurationService $configurationService;

    public function __construct(EmailService $emailService, ConfigurationService $configurationService)
    {
        $this->emailService = $emailService;
        $this->configurationService = $configurationService;
    }

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $this->sendEmailVerification($request->user());

        return back()->with('status', 'verification-link-sent');
    }

    public function sendEmailVerification(User $user)
    {
        $emailVerificationLink = HelperService::getEmailVerificationLink($user);
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = 'Email Verification';
        $emailData = ['user' => $user, 'emailVerificationLink' => $emailVerificationLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.email-verification', $emailData)->render();
        $this->emailService->send($user->email, $title, $emailContent);
    }
}
