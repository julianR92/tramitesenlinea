<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanosTipoLicencia extends Model
{
	protected $table = 'planos_tipo_licencia';
	protected $primaryKey = 'id';


	protected $fillable = [
		"tipo_licencia"
	];

	public function modalidades()
	{
		return $this->hasMany('App\PlanosModalidades', 'tipo_licencia_id');
	}
}
