<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Log;
class TestController extends Controller
{
    public function tokenfacebook(Request $request){
		$fb_access_token = "EAAdW8SMjr4sBAPVcTylDZBXaJfF4xZCOWNSAAYuHauUrd7ZAR7l6cGuw8ynf5ZCX8xtZA2j1sUFTjAmHnanYZB8lXRJgT1FlVdEOTH41A9IbkBXKSZAWP1rUe4oLuL60gYAgyYqycTaXztqHO3q7Cr7XBt05Q3qEVXsbAZB6OkOou1PRX20RS2BzIeZATZCx03bvkZD";
		$fb_graph_url = "https://graph.facebook.com/v2.12/";//version actual al 03/2018 es v2.12

		$challenge= $_REQUEST["hub_challenge"];
		 $verify= $_REQUEST["hub_verify_token"];
		 if($verify=="abc123"){
			 return $challenge;
	 }

		$facebook_items = json_decode(file_get_contents('php://input'), true);
                return $facebook_items; 
		$myFile = "fb_log.txt";

		if($facebook_items){//Si el request no llego vacio procesamos
		  $facebook_entries = $facebook_items['entry'][0]['changes'];
		  if (file_exists($myFile)) {
			$fh = fopen($myFile, 'a');
		  } else {
			$fh = fopen($myFile, 'w');
		  }

		  foreach($facebook_entries as $request_item){
			if($request_item['field'] == 'leadgen'){//verificamos que sea del tipo leadgen el request, sino, no nos interesa
			  //obtenemos el leadgen_id
			  $fb_leadgen_id = $request_item['value']['leadgen_id'];

			  if($fb_leadgen_id != ''){//si no es vacio generamos la peticion al api de facebook para obtener los datos del formulario
				$ch = curl_init($fb_graph_url.$fb_leadgen_id."?access_token=".$fb_access_token);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				if($content  = curl_exec($ch)){
				  $facebook_lead_data = json_decode($content, true);

				  $message = "";
				  $message .= $resultado['field_data'][0]['name'].": ".$resultado['field_data'][0]['values'][0]."\n";
				  $message .= $resultado['field_data'][1]['name'].": ".$resultado['field_data'][1]['values'][0]."\n";

				  fwrite($fh, $message."\n");

				  //obtengo el email
				  //$resultado['field_data'][0]['name']
				  //$resultado['field_data'][0]['values'][0]

				  //obtengo el nombre
				  //$resultado['field_data'][1]['name']
				  //$resultado['field_data'][1]['values'][0]


				}else{//Algo anduvo mal al hacer el curl

				}
				curl_close($ch);
			  }
			}else{
			  //loguemos que no es el tipo leadgen?
			}
		  }

		  fclose($fh);
		}

		return "1";
    }
  
    
    public function showLog(Request $request ){
          $logFile = file(storage_path().'/logs/laravel.log');
          $cont=0;
          echo "<pre>";
          $maxLines=200;
          if($request->has("maxLines")){
              $maxLines=$request->get("maxLines");
          }
           foreach (array_reverse($logFile)  as $line_num => $line) {
               if($cont<$maxLines){
                   echo htmlspecialchars($line)."<br>" ;
               }
            $cont++; 
        }
        dd("fin");

    }
    
}
