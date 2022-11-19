<?php

// Socialite не делал Github, может нужно удалить??

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Services\Socialite\Contract\Social;

class SocialAuthController extends Controller
{
    // Socialite не делал Github, может нужно удалить??
    public function show(): Application|Factory|View
    {
        return view('front.auth.login');
    }

    public function redirect(string $driver): RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();

        } catch (\Throwable $exception) {
            throw new \DomainException('Произошла ошибка или драйвер не поддерживается');
        }

    }

    public function callback(Social $social, string $driver): RedirectResponse
    {
        flash()->info(__('auth.success_login'));

        return redirect(
            $social->loginSocial(Socialite::driver($driver)->user())
        );
    }
}
