<?php

namespace OCA\test\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCP\User;
use \OCA\AppFramework\Http\TemplateResponse;
use \OCA\AppFramework\Http\Request;

class PageController extends Controller {


    public function __construct($api, $request){
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
    	$response = new TemplateResponse($this->api, 'main');
    	$response->renderAs("user");
    	$params = array('messages' => $message,
    			'msg' => "hallo sch welt...");
    	$response->setParams($params);
		$response->render();
    	return $this->render('main', array('messages' => "hallo again"));
    }
}
