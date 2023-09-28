<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Providers\RouteServiceProvider;
use Auth0\Laravel\Facade\Auth0;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

 
class LoginController extends Controller
{

    use AuthenticatesUsers {
        validateLogin as vendorValidateLogin;
        attemptLogin as vendorAttemptLogin;
        credentials as vendorCredentials;

    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showLoginForm(Request $request)
    {
        //$url = Auth0::getSdk()->authentication()->getLoginLink(csrf_token(), url('/admin/callback'));

        return view('login');
            //->with('google_login_url', $url);
    }
    public function getCallback(Request $request)
    {
        if (!$request->state || $request->state != csrf_token()) {
            return redirect('/admin/login')
                ->withErrors('Something went wrong. Please try again.');
        }

        $client = new \App\Services\Auth0Client;

        try {
            $response = $client->oauthToken($request->code, url('/'));
            $response = $client->userinfo($response['access_token']);
        } catch (\App\Exceptions\Auth0Exception $exception) {
            $content = $exception->getContent();

            Log::error($exception);

            return redirect('/admin/login')->withErrors('Something went wrong. Please try again.');
        }

        $user = $this->findOrCreateUser($response);

        Auth::login($user);

        return redirect()->intended('/admin');
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => config('services.google.recaptcha.enabled') ? ['required', 'recaptcha'] : [],
        ]);
        $this->vendorValidateLogin($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if ($request->login_redirect_url) {
            redirect()->setIntendedUrl(url($request->login_redirect_url));
        }

        $this->guest_cart = cart();

        if (auth0_enabled()) {
            $client = new \App\Services\Auth0Client;

            try {
                $response = $client->login($request->email, $request->password);
                $response = $client->userinfo($response['access_token']);
            } catch (\App\Exceptions\Auth0Exception $exception) {
                $content = $exception->getContent();

                if (($content['error'] ?? null) == 'invalid_grant') {
                    return false;
                }

                Log::error($exception);

                return false;
            }

            $user = $this->findOrCreateUser($response);

            Auth::login($user);

            return true;
        }

        return $this->vendorAttemptLogin($request);
    }

    private function findOrCreateUser(array $fields)
    {
        $user = User::where('auth0_user_id', $fields['sub'])->first();

        if (!$user) {
            $user = User::where('email', $fields['email'])->first();
        }

        if (!$user) {
            $user = new User;
            $user->type = User::TYPE_CUSTOMER;
            $user->status = User::STATUS_ACTIVE;
        }

        $user->auth0_user_id = $fields['sub'];

        if ($fields['given_name'] ?? null) {
            $user->first_name = $fields['given_name'];
        }

        if ($fields['family_name'] ?? null) {
            $user->last_name = $fields['family_name'];
        }

        $user->email = $fields['email'];
        $user->password = null;
        $user->last_login_date = now();
        $user->last_login_ip = request()->ip();
        $user->save();

        return $user;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $this->vendorCredentials($request);
        $credentials += ['status' => 'active'];

        return $credentials;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (! $user->isAdmin()) {
            Auth::logout();

            return $this->sendFailedLoginResponse($request);
        }

        $this->handleLogon($request, $user);
    }

    protected function handleLogon(Request $request)
    {
        $user = Auth::user();
        // Transfer guest cart to user
        $guest_cart = $this->guest_cart;

        if ($guest_cart->items()->count()) {
            $user->cart_id = $guest_cart->id;
        }

        $user->last_login_date = Carbon::now()->toDateTimeString();
        $user->last_login_ip = $request->ip();
        $user->save();

        addlog('Logged in via Admin');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function logout(Request $request): RedirectResponse{
        Auth::logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}