<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteImage extends Model
{
    public $primaryKey = 'id';
    protected $table = 'site_image';

    public function site()
    {
        return $this->hasOne("App\Site","id","site_id");
    }
    public function getImageBySiteImage($site,$image){
        return $this
                ->where("site_id",$site)
                ->where("id",$image)
                ->first();
    }
}



