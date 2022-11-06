<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use function Clue\StreamFilter\fun;

class AuthController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('auth.index');
    }

    public function signUp(): Factory|View|Application
    {
        return view('auth.sign-up');
    }

    public function forgot(): Factory|View|Application
    {
        return view('auth.forgot-password');
    }

//    public function forgotPassword(){
//        return view('auth.forgot-password');
//    }

    public function signIn(SignInFormRequest $request): RedirectResponse
    {
        if(!auth()->attempt($request->validated())){
            return back()->withErrors([
                'email' => __('Предоставленные учетные данные не соответствуют нашим записям.'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('home'));
    }

    public function store(SignUpFormRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        event(new Registered($user));//закомменитировал в EventServiceProvider строку №20: SendEmailVerificationNotification::class

        auth()->login($user);

        return redirect()
            ->intended(route('home'));
    }

    public function logOut(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()
            ->intended(route('home'));
    }

    public function forgotPassword(ForgotPasswordFormRequest $request): RedirectResponse
    {

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['message' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function reset(string $token): Factory|View|Application
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    public function resetPassword(ResetPasswordFormRequest $request): RedirectResponse
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(str()->random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('message', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
