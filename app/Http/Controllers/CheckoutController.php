<?php

namespace App\Http\Controllers;

//use Gloudemans\Shoppingcart\Cart;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::content()->count() == 0)
        {
            Session::flash('info', 'Your cart is still empty. do some shopping!');

            return redirect()->back();
        }
        return view('checkout');
    }

    public function pay()
    {

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
//        Stripe::setApiKey("sk_test_cXdLsdvO874Pid67FgZGNX7T");
        Stripe::setApiKey("sk_test_5FVxZgKELMh1Lh8MiHaRC8p5");
//        Stripe::setApiKey("sk_test_62bQpTPhmixDjY5bAiL5Mam8");

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:

        $charge = Charge::create([
            'amount' => Cart::total() * 100,
            'currency' => 'usd',
            'description' => 'practice selling books',
            'source' => \request()->stripeToken
        ]);

//        dd('your card was charged successfully.');

        Session::flash('success', 'Purchase successful. wait for our email.');

        Cart::destroy();

        Mail::to(\request()->stripeEmail)->send(new \App\Mail\PurchaseSuccessful);

        return redirect('/');
    }
}
