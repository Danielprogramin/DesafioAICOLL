<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'telefono',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'Activo',
    ];

    public function scopeActive($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function scopeInactive($query)
    {
        return $query->where('estado', 'Inactivo');
    }

    public function isActive(): bool
    {
        return $this->estado === 'Activo';
    }

    public function isInactive(): bool
    {
        return $this->estado === 'Inactivo';
    }
}
