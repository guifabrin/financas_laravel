<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\UserOauth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return \Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $userSocialite = \Socialite::driver($provider)->user();
        $id = $userSocialite->getId();
        $name = $userSocialite->getName();
        $email = $userSocialite->getEmail();
        $avatar = $userSocialite->getAvatar();
        $userOauth = UserOauth::where('uuid',$userSocialite->getId())->first();
        if ($userOauth == null){
            $user = User::where('email',$userSocialite->getEmail())->first();
            if ($user==null){
                $user = new User;
                $user->name = $userSocialite->getName();
                $user->email = $userSocialite->getEmail();
                $user->picture = $userSocialite->getAvatar();
                $user->save();
            }
            $userOauth = new UserOauth;
            $userOauth->uuid = $userSocialite->getId();
            $userOauth->name = $userSocialite->getName();
            $userOauth->email = $userSocialite->getEmail();
            $userOauth->avatar = $userSocialite->getAvatar();
            $userOauth->user_id = $user->id;
            
        } else {

        }
    }
}
