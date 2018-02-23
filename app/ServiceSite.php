<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceSite extends Model
{
    public $primaryKey = 'id';
    protected $table = 'service_site';

	public function service()
    {
        return $this->hasOne('App\Service','id', 'service_id');
    }
  
   
}
