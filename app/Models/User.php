<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function units()
	{
		return $this->belongsToMany('App\Models\Unit')->withPivot(['importer', 'coord'])->orderBy('name');
	}

    public function getCoordAttribute()
    {
        foreach($this->units as $unit)
        {
            if($unit->pivot->coord) return true;
        }
        return false;
    }

    public function getImporterAttribute()
    {
        foreach($this->units as $unit)
        {
            if($unit->pivot->importer) return true;
        }
        return false;
    }

    public function getMyCoordUnitsAttribute()
    {
        return $this->units()->where('coord', true)->get();
    }

    public function getMyImportUnitsAttribute()
    {
        return $this->units()->where('importer', true)->get();
    }

    public function getActiveAttribute($value)
    {
        return ($value > 0) ? true : false;
    }
}
