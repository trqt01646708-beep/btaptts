<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Sync session cart to database after registration.
     */
    protected function syncCartAfterLogin(Request $request)
    {
        $sessionCart = $request->session()->get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $details) {
                // For new users, there won't be existing DB items, but let's be safe
                CartItem::updateOrCreate(
                    ['user_id' => Auth::id(), 'product_id' => $productId],
                    ['quantity' => $details['quantity']]
                );
            }
            $request->session()->forget('cart');
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:4'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Sync items added as guest
        $this->syncCartAfterLogin($request);

        return redirect(route('home', absolute: false));
    }
}
