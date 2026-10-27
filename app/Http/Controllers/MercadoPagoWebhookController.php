<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoClient;
use App\Services\MercadoPagoException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $request, MercadoPagoClient $mercadoPago): Response
    {
        $topic = $request->input('type') ?? $request->query('topic') ?? $request->query('type');
        $paymentId = $request->input('data.id') ?? $request->query('id') ?? $request->query('data.id');

        if ($topic !== 'payment' || ! is_scalar($paymentId) || (string) $paymentId === '') {
            return response()->noContent();
        }

        try {
            $payment = $mercadoPago->payment((string) $paymentId);
        } catch (MercadoPagoException) {
            return response()->noContent(503);
        }

        $order = Order::find($payment['external_reference'] ?? null);

        if ($order) {
            $order->aplicarPago($payment);
        }

        return response()->noContent();
    }
}
