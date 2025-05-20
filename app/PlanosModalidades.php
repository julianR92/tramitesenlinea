<?php

namespace App;

use App\DocUpdate;

use Illuminate\Database\Eloquent\Model;

class PlanosModalidades extends Model
{
	protected $table = 'planos_modalidades';
	protected $primaryKey = 'id';


	protected $fillable = [
		"tipo_licencia_id",
		"modalidad"
	];

	public function tipoLicencia()
	{
		return $this->belongsTo('App\PlanosTipoLicencia', 'tipo_licencia_id');
	}
}
