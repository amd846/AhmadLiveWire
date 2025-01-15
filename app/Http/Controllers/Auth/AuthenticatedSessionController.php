<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
       // dd($request->session()->regenerate());
       // Fire the UserLoggedIn event
        event(new UserLoggedIn(Auth::user(), $request));
        $dashboard='user';
        if(Auth::user()->userRole=='user'){
            $dashboard='/user/dashboard';
        } else  if(Auth::user()->userRole=='admin'){
            $dashboard='/admin/dashboard';
        } else  if(Auth::user()->userRole=='supervisor'){
            $dashboard='/supervisor/dashboard';
        }

     //   dd($dashboard);
    //    return redirect()->intended(route('dashboard', absolute: false));
         // Redirect to the appropriate dashboard
    return redirect()->to($dashboard);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
      //  dd(Auth::user());
        event(new UserLoggedOut(Auth::user(), $request));
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
