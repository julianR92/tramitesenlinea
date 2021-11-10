<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boletas extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'tipo_boleteria';
    protected $primaryKey = 'id';
    
}
