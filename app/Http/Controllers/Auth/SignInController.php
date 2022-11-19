<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SignInController extends Controller
{
//    public function index(): Factory|View|Application
    public function page(): Factory|View|Application
    {
        return view('auth.login');
    }

//    public function signIn(SignInFormRequest $request): RedirectResponse
    public function handle(SignInFormRequest $request): RedirectResponse
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

    public function logOut(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()
            ->intended(route('home'));
    }
}
