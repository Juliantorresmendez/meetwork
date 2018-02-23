<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $primaryKey = 'id';


    public function findCity($name){
    	return $this->where("name",$name)->first();
    }
   
}
