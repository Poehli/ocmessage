<?php

namespace OCA\OCMessage\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Core\API;
use \OCP\User;
use \OCA\AppFramework\Http\TemplateResponse;
use \OCA\AppFramework\Http\Request;

class PageController extends Controller {


    public function __construct(API $api,Request $request){
        parent::__construct($api, $request);
    }


    /**
     * ATTENTION!!!
     * The following comments turn off security checks
     * Please look up their meaning in the documentation!
     *
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function index(){
    	$query = \OCP\DB::prepare("SELECT * FROM *PREFIX*msg WHERE message_to=?");
    	
    	$results = $query->execute(array(USER::getUser()));
    	
    	$message = $results->fetchAll();
    	
    	// Zeit Menschenleslich machen
    	for ($i = 0; $i < sizeof($message); $i++){
    		$time = $message[$i]["message_timestamp"];
    		if (date("Ynj", $time) == date("Ynj")){
    			if (date("G", $time)+2 >= date("G") ){
    				$message[$i]["message_timestamp"] = "heute um ".date("G:i", $time);
    			} else {
    				if (date("G", $time) + 1 >= date("G")){
    					$message[$i]["message_timestamp"] = "vor einer Stunde";
    				} else {
    					$message[$i]["message_timestamp"] = "vor wenigen Minuten";
    				}
    			}
    		} else {
    			
    			if (date("Yn", $time)!= date("Yn") || intval(date("j", $time))+2 > intval(date("j"))){
    				$message[$i]["message_timestamp"] = "am ". date("j.n.Y", $time).", um ".date("G:i", $time);
    			} else if(date("j", $time)+1 >= date("j")) {
    				$message[$i]["message_timestamp"] = "vorgestern um ".date("G:i");
    			} else {
    				$message[$i]["message_timestamp"] = "gestern um ".date("G:i");
    			}
    		}
    	}
    	
    	$users = \OCP\User::getUsers();
    	return $this->render('main', array('messages' => $message,
    										'users'=>$users));
    }
}
