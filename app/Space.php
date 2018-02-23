<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    public $primaryKey = 'id';


    public function findByName($name){
    	return $this->where("name",$name)->first();
    }
   
}
