<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MercadoPagoClient
{
    public function createPreference(Order $order): array
    {
        $token = $this->token();
        $items = [];

        foreach ($order->items as $item) {
            $variante = '';

            if (! empty($item['marco'])) {
                $variante = 'Marco: '.$item['marco'];
            } elseif (! empty($item['talle'])) {
                $variante = 'Talle: '.$item['talle'];
            }

            $items[] = [
                'title' => trim($item['nombre'].($variante !== '' ? ' - '.$variante : '')),
                'quantity' => (int) $item['cantidad'],
                'unit_price' => (float) $item['precio'],
                'currency_id' => 'ARS',
            ];
        }

        $payload = [
            'items' => $items,
            'external_reference' => (string) $order->id,
            'back_urls' => [
                'success' => route('pago.show', 'exitoso'),
                'failure' => route('pago.show', 'fallido'),
                'pending' => route('pago.show', 'pendiente'),
            ],
            'auto_return' => 'approved',
        ];

        if (str_starts_with((string) config('app.url'), 'https://')) {
            $payload['notification_url'] = route('webhooks.mercadopago');
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->timeout(20)
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);

        $body = $this->jsonOrFail($response, 'Mercado Pago no pudo crear el pago.');
        $checkout = str_starts_with($token, 'TEST-')
            ? ($body['sandbox_init_point'] ?? null)
            : ($body['init_point'] ?? null);

        if (! is_string($checkout) || $checkout === '') {
            throw new MercadoPagoException('Mercado Pago no devolvió la URL de pago.');
        }

        return [
            'id' => $body['id'] ?? null,
            'init_point' => $checkout,
        ];
    }

    public function payment(string $paymentId): array
    {
        $response = Http::withToken($this->token())
            ->acceptJson()
            ->timeout(20)
            ->get('https://api.mercadopago.com/v1/payments/'.rawurlencode($paymentId));

        return $this->jsonOrFail($response, 'No se pudo consultar el pago.');
    }

    private function token(): string
    {
        $token = trim((string) config('services.mercadopago.access_token'));

        if ($token === '' || str_contains($token, 'XXXX')) {
            throw new MercadoPagoException('Mercado Pago no está configurado.');
        }

        return $token;
    }

    private function jsonOrFail(Response $response, string $message): array
    {
        if (! $response->successful()) {
            throw new MercadoPagoException($message);
        }

        $body = $response->json();

        if (! is_array($body)) {
            throw new MercadoPagoException($message);
        }

        return $body;
    }
}
