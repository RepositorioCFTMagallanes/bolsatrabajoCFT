<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    public $timestamps = false;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'rol_id',
        'rut',
        'nombre',
        'apellido',
        'email',
        'contrasena',
        'email_verificado_en',
        'token_recordar',
        'creado_en',
        'actualizado_en',
    ];

    protected $hidden = [
        'contrasena',
        'token_recordar',
    ];

    // 🔑 Laravel necesita saber cuál es el password
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // Mutator: hash automático
    public function setContrasenaAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['contrasena'] = Hash::make($value);
        }
    }

    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'usuario_id');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'usuario_id');
    }
}
