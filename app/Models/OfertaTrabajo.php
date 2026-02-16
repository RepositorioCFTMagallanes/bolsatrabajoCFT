<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\AreaEmpleo;
use App\Models\Empresa;
use App\Models\TipoContrato;
use App\Models\Modalidad;
use App\Models\Jornada;
use App\Models\Postulacion;
use App\Models\OfertaFavorita;

class OfertaTrabajo extends Model
{
    protected $table = 'ofertas_trabajo';
    public $timestamps = true;

    /** ============================
     *  Estados del Workflow de Ofertas
     *  ============================ */

    const ESTADO_PENDIENTE   = 0;
    const ESTADO_APROBADA    = 1;
    const ESTADO_RECHAZADA   = 2;
    const ESTADO_REENVIADA   = 3;
    const ESTADO_FINALIZADA  = 4;




    protected $fillable = [
        'empresa_id',
        'titulo',
        'area_id',
        'tipo_contrato_id',
        'modalidad_id',
        'jornada_id',
        'vacantes',
        'region',
        'ciudad',
        'direccion',
        'sueldo_min',
        'sueldo_max',
        'mostrar_sueldo',
        'beneficios',
        'requisitos',
        'descripcion',
        'habilidades_deseadas',
        'ruta_archivo',
        'nombre_contacto',
        'correo_contacto',
        'telefono_contacto',
        'fecha_cierre',
        'estado',
        'creado_en',
        'actualizado_en',
    ];
    protected $casts = [
    'estado'        => 'integer',
    'fecha_cierre'  => 'date',
    'creado_en'     => 'datetime',
    'actualizado_en'=> 'datetime',
];



    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function area()
    {
        return $this->belongsTo(AreaEmpleo::class, 'area_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'oferta_id');
    }
    public function tipoContrato()
    {
        // Tabla real: tipos_contrato, PK: id
        return $this->belongsTo(TipoContrato::class, 'tipo_contrato_id');
    }

    public function modalidad()
    {
        // Tabla real: modalidades, PK: id
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    public function jornada()
    {
        // Tabla real: jornadas, PK: id
        return $this->belongsTo(Jornada::class, 'jornada_id');
    }

    public function favoritos()
    {
        // Tabla real: ofertas_favoritas, FK: oferta_id
        return $this->hasMany(OfertaFavorita::class, 'oferta_id');
    }


    /** ============================
     *  Accessor fecha publicación
     *  ============================ */
    public function getFechaPublicacionAttribute()
    {
        if (!empty($this->creado_en)) {
            return \Carbon\Carbon::parse($this->creado_en);
        }

        if (!empty($this->actualizado_en)) {
            return \Carbon\Carbon::parse($this->actualizado_en);
        }

        return now();
    }

    /** ============================
     *  Accessor legible para admin
     *  ============================ */
    public function getEstadoNombreAttribute()
    {
        return match ((int)$this->estado) {
            self::ESTADO_APROBADA    => 'Aprobada',
            self::ESTADO_RECHAZADA   => 'Rechazada',
            self::ESTADO_REENVIADA   => 'Reenviada',
            self::ESTADO_FINALIZADA  => 'Finalizada',
            default                  => 'Pendiente',
        };
    }

    /** ============================
     *  Scope: Ofertas vigentes (públicas)
     *  ============================ */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query
            ->where('estado', self::ESTADO_APROBADA)
            ->where(function ($q) {
                $q->whereNull('fecha_cierre')
                    ->orWhere('fecha_cierre', '>=', now()->toDateString());
            });
    }

    public function esFavorita($estudianteId)
    {
        return $this->favoritos()->where('estudiante_id', $estudianteId)->exists();
    }
}
