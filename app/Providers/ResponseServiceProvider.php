<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('api', function ($data,$success) use ($factory) {
            if($success){
				$customFormat = [
	                'success' => true,
	                'data' => $data
	            ];
            }else{
            	$customFormat = [
	                'error' => true,
	                'message' => $data
            	];
            }
            
            return $factory->make($customFormat);
        });
    }

    public function register()
    {
        //
    }
}