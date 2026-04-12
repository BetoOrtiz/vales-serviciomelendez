<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'clave',
        'valor',
        'tipo',
    ];

    protected $table = 'configuraciones';

    public function scopeFiltros($query, array $filtros)
    {
        $query->when($filtros['clave'] ?? null, function ($query, $clave) {
            return $query->where('clave', $clave);
        })->when($filtros['valor'] ?? null, function ($query, $valor) {
            return $query->where('valor', $valor);
        })->when($filtros['tipo'] ?? null, function ($query, $tipo) {
            return $query->where('tipo', $tipo);
        });
    }

    public function scopeFiltro($query, $key)
    {
        return $query->orWhere('clave', 'LIKE', "%{$key}%")
                ->orWhere('valor', 'LIKE', "%{$key}%")
                ->orWhere('tipo', 'LIKE', "%{$key}%");
    }
}