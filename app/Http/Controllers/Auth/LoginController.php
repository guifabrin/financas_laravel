<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\UserOauth;
use App\User;

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
    $userOauth = UserOauth::where('uuid',$userSocialite->getId())->first();
    if ($userOauth == null){
      Log::info('User Oauth not found');
      if(Auth::guest()){
        Log::info('User not logged');
        $user = User::where('email',$userSocialite->getEmail())->first();
        if ($user==null){
          $user = new User;
          $user->name = $userSocialite->getName();
          $user->email = $userSocialite->getEmail();
          $user->picture = $userSocialite->getAvatar();
          $user->save();
        }
      } else {
        Log::info('User logged');
        $user = Auth::user();
      }
      $userOauth = new UserOauth;
      $userOauth->uuid = $userSocialite->getId();
      $userOauth->name = $userSocialite->getName();
      $userOauth->email = $userSocialite->getEmail();
      $userOauth->avatar = $userSocialite->getAvatar();
      $userOauth->provider = $provider;
      $userOauth->user()->associate($user);
      $userOauth->save();
    }
    Auth::login($userOauth->user);
    Log::info(['User Oauth', $userOauth->toJson(), $userOauth->user->toJson(), Auth::check()]);
    return redirect($this->redirectTo);
  }
}
