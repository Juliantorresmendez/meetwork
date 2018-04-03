<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public $primaryKey = 'id';


   public function getByLeadId($lead){
       return $this->where('id_lead',$lead)->count();
   }
  
   
}
