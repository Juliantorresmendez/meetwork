<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpaceSite extends Model
{
    public $primaryKey = 'id';
    protected $table = 'site_space';


  public function deleteAllBySite($id){
        return $this->where("site_id",$id)->delete();
    }
   
}
