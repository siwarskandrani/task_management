<?php
namespace App\Http\Controllers;

use App\Models\SocialLogin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback($driver)
    {
        try {
            $socialUser = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed!');
        }

        // Vérifiez si l'utilisateur existe déjà, sinon créez un nouvel utilisateur
        $user = User::firstOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName(),
            'password' => bcrypt('password'), // Définir un mot de passe par défaut
        ]);

        // Créez ou mettez à jour la connexion sociale
        SocialLogin::updateOrCreate(
            ['provider' => $driver, 'provider_id' => $socialUser->getId()],
            ['user_id' => $user->id]
        );

        Auth::login($user, true);

        return redirect()->route('dashboard'); // Redirige vers la page d'accueil ou une autre page après l'authentification
    }
}
