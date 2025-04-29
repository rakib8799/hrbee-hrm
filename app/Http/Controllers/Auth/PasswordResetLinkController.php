<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ConfigurationService;
use App\Services\Core\HelperService;
use App\Services\EmailService\EmailService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    protected UserService $userService;
    private EmailService $emailService;
    private ConfigurationService $configurationService;

    public function __construct(UserService $userService, EmailService $emailService, ConfigurationService $configurationService)
    {
        $this->userService = $userService;
        $this->emailService = $emailService;
        $this->configurationService = $configurationService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
            'pageTitle' => __('pageTitle.custom.auth.forgotPassword')
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            $this->sendResetPasswordEmail($this->userService->getUserByEmail($request));
            // return back()->with('status', __($status));
        }

        $reponseData = [
            'pageTitle' => __('pageTitle.custom.auth.forgotPasswordConfirmation')
        ];
        return Inertia::render('Auth/ForgotPasswordConfirmation', $reponseData);

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)],
        // ]);
    }

    public function sendResetPasswordEmail(User $user)
    {
        $resetPasswordLink = HelperService::getResetPasswordLink($user);
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = 'Reset Password';
        $emailData = ['user' => $user, 'resetPasswordLink' => $resetPasswordLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.reset-password', $emailData)->render();
        $this->emailService->send($user->email, $title, $emailContent);
    }
}
