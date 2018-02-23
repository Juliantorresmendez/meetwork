<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $primaryKey = 'id';


    public function findType($name){
    	return $this->where("name",$name)->first();
    }
   
}
