<?php

namespace OCA\OCMessage\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Core\API;
use \OCA\AppFramework\Http\Request;
use \OCA\AppFramework\Http\JSONResponse;
use \OCP\User;
use \OCA\OCMessage\Db\MessageRepository;

class MessageController extends Controller{
	public function __construct(API $api,Request $request){
		parent::__construct($api, $request);
	}
	
	public function getMessages(){
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		
		if ($user == "" || User::userExists($user)){
			$msg = new MessageRepository();
			$msgs = $msg->getMessages();
			
			return new JSONResponse(array('error' => $error_msg,	
										'return'  => $msgs));
		} else {
			$error = "user does not exist ($user)";
			return new JSONResponse(array('error' => $error_msg));
		}
		
		return new JSONResponse(array('error' => $error_msg));
	}
	
	public function getSendMessages(){
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		
		if ($user == "" || User::userExists($user)){
			$msg = new MessageRepository();
			$msgs = $msg->getSendMessages();
			
			return new JSONResponse(array('error' => $error_msg,	
										'return'  => $msgs));
		} else {
			$error = "user does not exist ($user)";
			return new JSONResponse(array('error' => $error_msg));
		}
		
		return new JSONResponse(array('error' => $error_msg));
	}
	
	
	public function getUnreadMessages(){		
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		
		if ($user == "" || User::userExists($user)){
			$msg = new MessageRepository();
			$msgs = $msg->getUnreadMessages();
			
			return new JSONResponse(array('error' => $error_msg,	
										'return'  => $msgs));
		} else {
			$error = "user does not exist ($user)";
			return new JSONResponse(array('error' => $error_msg));
		}
		
		return new JSONResponse(array('error' => $error_msg));
	}

	
	public function sendMessage(){
		$splitsign = array(";", ",");
		
		$now = new DateTime();
		$now = $now->getTimestamp();
		
		$users = array($this->params('msg_to'));
		
		// Splitte alle User von der Übergabe
		for ($i = 0; $i < sizeof($splitsign); $i++){
			$tmpuser = array();
			foreach ($users as $user){
				$users_split = explode($splitsign[$i],$user);
				$tmpuser = array_merge($tmpuser, $users_split);
			}
			$users = $tmpuser;
		}
		
		$error_msg ="";
		foreach ($users as $user){
			$user = trim($user);
			if ($user == ""){
				continue;
			}
		
			if (! User::userExists($user)){
				$error_msg .= "User does not exist: $user ;";
				continue;
			}
		
		
			$message = new \OCA\test\Controller\MessagesQuery();
			$ok = $message->sendMessage($user, $this->params("msg_subject"), $this->params("msg_content"));
		
		
			if (!isset($ok) || !$ok){
				$error_msg .= "An unknown error occured! ;";
			}
		}
		
		
		
		return new JSONResponse(array('error' => $error_msg,
									'return' => isset($ok)));
	
	}
	
	public function setMessageRead(){
		
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		$msgs;
		if ($user == "" || User::userExists($user)){
			$msg_id = $this->params("msg_id");
			$msg = new MessageRepository();
			$msgs = $msg->setMessageRead($msg_id);
		} else {
			
			$error = "User does not exists! ($user)";
		}
		return new JSONResponse(array('error' => $error,
									'return' => isset($msgs)));
	}
	
	
	
	public function setAllMessagesRead(){
		
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		
		$error = "";
		$msgs;
		if ($user == "" || User::userExists($user)){
			$msg_id = $this->params("msg_id");
			$msg = new MessageRepository();
			$msgs = $msg->setAllMessagesRead();
		} else {
			
			$error = "User does not exists! ($user)";
		}
		
		return new JSONResponse(array('error' => $error,
									'return' => $msgs));
	}
	
	public function hasUnreadMessage(){
		
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		$msgs;
		if ($user == "" || User::userExists($user)){
			$msg_id = $this->params("msg_id");
			$msg = new MessageRepository();
			$msgs = $msg->hasUnreadMessage();
		} else {
			
			$error = "User does not exists! ($user)";
		}
		
		return new JSONResponse(array('error' => $error,
									'return' => $msgs));
	}
	
	public function unreadMessageCount(){
		
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		$msgs;
		if ($user == "" || User::userExists($user)){
			$msg_id = $this->params("msg_id");
			$msg = new MessageRepository();
			$msgs = $msg->unreadMessageCount();
		} else {
			
			$error = "User does not exists! ($user)";
		}
		return new JSONResponse(array('error' => $error,
									'return' => $msgs));
	}
}

?>