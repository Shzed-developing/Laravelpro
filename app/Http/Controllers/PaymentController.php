<?php

namespace App\Http\Controllers;

use App\Helpers\Cart\Cart;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment as ShetabitPayment;

class PaymentController extends Controller
{
    public function payment()
    {
        $cart = Cart::instance('cart-roocket');
        $cartItems =  $cart->all();
        if ($cartItems->count()) {
            $price = $cartItems->sum(function ($cart) {
                return $cart['discount_percent'] == 0
                    ? $cart['product']->price * $cart['quantity']
                    : ( $cart['product']->price - ($cart['product']->price * $cart['discount_percent'] )) * $cart['quantity'];
            });

            $orderItems = $cartItems->mapWithKeys(function ($cart) {
               return [$cart['product']->id => ['quantity' => $cart['quantity']] ];
            });

            $order = auth()->user()->orders()->create([
               'status' => 'unpaid',
                'price' => $price
            ]);

            $order->products()->attach($orderItems->toArray());

            // Create new invoice.
            $invoice = (new Invoice)->amount(1000);
            return ShetabitPayment::callbackUrl(route('payment.callback'))->purchase($invoice, function($driver, $transactionId) use ($order, $cart, $invoice) {

                $order->payments()->create([
                    'resnumber' => $invoice->getUuid(),
                ]);

                $cart->flush();

            })->pay()->render();

        }
        return back();
    }

    public function callback(Request $request)
    {
        try {
            $payment = Payment::where('resnumber', $request->clientrefid)->firstOrFail();

            // $payment->order->price
            $receipt = ShetabitPayment::amount(1000)->transactionId($request->clientrefid)->verify();

            $payment->update([
                'status' => 1
            ]);

            $payment->order()->update([
                'status' => 'paid'
            ]);

            Alert::success('سفارش شما با موفقیت ثبت شد');
            return redirect('/products');

        } catch (InvalidPaymentException $exception) {
            /**
             * when payment is not verified, it will throw an exception.
             * We can catch the exception to handle invalid payments.
             * getMessage method, returns a suitable message that can be used in user interface.
             **/
            return $exception->getMessage();

            Alert::error($exception->getMessage());
            return redirect('/products');
        }
    }
}
