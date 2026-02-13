<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';

    public $timestamps = true;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'run',
        'estado_carrera',
        'carrera',
        'telefono',
        'ciudad',
        'resumen',
        'institucion',
        'anio_egreso',
        'cursos',
        'linkedin_url',
        'portfolio_url',
        'area_interes_id',
        'jornada_preferencia_id',
        'modalidad_preferencia_id',
        'visibilidad',
        'frecuencia_alertas',
        'creado_en',
        'actualizado_en',

        // BLOB
        'avatar_blob',
        'avatar_mime',
        'cv_blob',
        'cv_mime',
    ];

    protected $casts = [
        'avatar_mime' => 'string',
        'cv_mime' => 'string',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id')->withTrashed();
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'estudiante_id');
    }
}
