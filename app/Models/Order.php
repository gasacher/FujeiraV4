<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'numero',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
        'items',
        'total',
        'estado',
        'mp_preference_id',
        'mp_payment_id',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'total' => 'integer',
        ];
    }

    public function aplicarPago(array $payment): void
    {
        if (($payment['currency_id'] ?? null) !== 'ARS') {
            return;
        }

        if ((int) round((float) ($payment['transaction_amount'] ?? -1)) !== $this->total) {
            return;
        }

        if ((string) ($payment['external_reference'] ?? '') !== (string) $this->id) {
            return;
        }

        $this->mp_payment_id = isset($payment['id']) ? (string) $payment['id'] : $this->mp_payment_id;
        $this->estado = match ($payment['status'] ?? '') {
            'approved' => 'aprobado',
            'rejected', 'cancelled' => 'rechazado',
            default => 'pendiente',
        };
        $this->save();
    }
}
