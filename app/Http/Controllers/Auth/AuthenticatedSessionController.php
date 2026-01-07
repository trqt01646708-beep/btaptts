<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\CartItem;

class AuthenticatedSessionController extends Controller
{
    /**
     * Sync session cart to database after login.
     */
    protected function syncCartAfterLogin(Request $request)
    {
        $sessionCart = $request->session()->get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $details) {
                $cartItem = CartItem::where('user_id', Auth::id())
                                    ->where('product_id', $productId)
                                    ->first();
                
                if ($cartItem) {
                    $cartItem->quantity += $details['quantity'];
                    $cartItem->save();
                } else {
                    CartItem::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'quantity' => $details['quantity']
                    ]);
                }
            }
            
            // Clear session cart after syncing to DB
            $request->session()->forget('cart');
        }
    }

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

        // Sync session cart to database
        $this->syncCartAfterLogin($request);

        if (Auth::user()->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
