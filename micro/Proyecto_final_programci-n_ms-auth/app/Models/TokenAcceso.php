<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenAcceso extends Model
{
    protected $table = 'tokens_acceso';

    public $timestamps = false;

    protected $fillable = [
        'token',
        'usuario_id',
        'fecha_expiracion',
        'revocado',
    ];

    protected $casts = [
        'fecha_expiracion' => 'datetime',
        'revocado' => 'boolean',
    ];

    protected $hidden = [
        'token',
    ];

    public function credencial(): BelongsTo
    {
        return $this->belongsTo(UsuarioAuth::class, 'usuario_id', 'usuario_id');
    }

    public function esValido(): bool
    {
        if ($this->revocado || !$this->fecha_expiracion) {
            return false;
        }

        return $this->fecha_expiracion->isFuture();
    }

    public function revocar(): bool
    {
        return $this->update(['revocado' => true]);
    }
}
