<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        Log::info('Checkout request received', ['user_id' => $request->user_id]);

        $user = User::find($request->user_id);

        if (!$user) {
            Log::error('Checkout failed: User not found', ['user_id' => $request->user_id]);
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $intent = $user->createSetupIntent();
            Log::info('Checkout setup intent created', ['client_secret' => $intent->client_secret]);
            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Exception $e) {
            Log::error('Checkout error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment initialization failed'], 500);
        }
    }

    public function getStatus($id)
    {
        Log::info('Fetching payment status', ['payment_id' => $id]);

        $payment = Payment::find($id);

        if (!$payment) {
            Log::error('Payment status check failed: Payment not found', ['payment_id' => $id]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        return response()->json(['status' => $payment->status]);
    }

    public function history(Request $request)
    {
        Log::info('Fetching payment history', ['user_id' => $request->user_id]);

        $user = User::find($request->user_id);

        if (!$user) {
            Log::error('Payment history request failed: User not found', ['user_id' => $request->user_id]);
            return response()->json(['error' => 'User not found'], 404);
        }

        $payments = Payment::where('user_id', $user->id)->get();
        Log::info('Payment history retrieved', ['count' => count($payments)]);

        return response()->json($payments);
    }
}
