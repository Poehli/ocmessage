<?php

namespace OCA\OCMessage\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Core\API;
use \OCA\AppFramework\Http\Request;
use \OCA\AppFramework\Http\JSONResponse;
use \OCP\User;
use \OCA\OCMessage\Db\MessageRepository;
use \OCA\OCMessage\DependencyInjection\DIContainer;
use \OCA\OCMessage\Controller\BBCodes;

class MessageController extends Controller{
	public function __construct(API $api,Request $request){
		parent::__construct($api, $request);
	}
	
	
	private function convertBBs($msg){
		$bbs =new \OCA\OCMessage\Controller\BBCodes($msg);
		
		$bbs->addBBSet("br","br");
		$bbs->addBBSet("url=%a%","/url","a href='%a%'","/a");
		$bbs->addBBSet("url","/url","a href='$1'", "/a");
		$bbs->addBBSet("b","/b","b","/b");
		$bbs->addBBSet("u", "/u","u", "/u");
		$bbs->addBBSet("color=%a%","/color","font color='%a%'","/font");
		$bbs->addBBSet("size=%a%","/size","font size='%a%'","/font");
		$bbs->addBBSet("quote","/quote","code","/code");
		
		return $bbs->getHTML();
		return $msg;
	}
	
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function getMessages(){
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		
		
		$error = "";
		
		if ($user == "" || User::userExists($user)){
			$msg = new MessageRepository();
			$msgs = $msg->getMessages();
			if ($this->params("denyBB") == "true"){
				return new JSONResponse(array('error' => $error,	
										'return'  => $msgs));
			}
				
			for ($i = 0; $i < sizeof($msgs); $i++) {
				$msgs[$i]["message_content"] = $this->convertBBs($msgs[$i]["message_content"]);
				
			}

			return new JSONResponse(array('error' => $error,	
										'return'  => $msgs));
		} else {
			$error = "user does not exist ($user)";
			return new JSONResponse(array('error' => $error));
		}
		
		return new JSONResponse(array('error' => $error));
	}
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function getSendMessages(){
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		
		if ($user == "" || User::userExists($user)){
			$msg = new MessageRepository();
			$msgs = $msg->getSendMessages();
			
			return new JSONResponse(array('error' => $error,	
										'return'  => $msgs));
		} else {
			$error = "user does not exist ($user)";
			return new JSONResponse(array('error' => $error));
		}
		
		return new JSONResponse(array('error' => $error));
	}
	
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function getUnreadMessages(){		
		$user = $this->params("user");
		if (!isset($user)){
			$user = "";
		}
		$error = "";
		
		if ($user == "" || User::userExists($user)){
			$msg = new MessageRepository();
			$msgs = $msg->getUnreadMessages();
			
			return new JSONResponse(array('error' => $error,	
										'return'  => $msgs));
		} else {
			$error = "user does not exist ($user)";
			return new JSONResponse(array('error' => $error));
		}
		
		return new JSONResponse(array('error' => $error));
	}

	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function sendMessage(){
		
		
		$splitsign = array(";", ",");
		
		$now = new \DateTime();
		$now = $now->getTimestamp();
		$msg_cont = $this->params("msg_content");
		$msg_content = str_replace("\n","[br]", $msg_cont);
		$msg_content = str_replace("\r","[br]", $msg_cont);
		$msg_subject = htmlentities($this->params("msg_subject"));
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
		
		$error ="";
		foreach ($users as $user){
			$user = trim($user);
			if ($user == ""){
				continue;
			}
		
			if (! User::userExists($user)){
				$error .= "User does not exist: $user ;";
				continue;
			}
		
		
			$message = new \OCA\OCMessage\Db\MessageRepository();
			$ok = $message->sendMessage($user, $msg_subject , $msg_content);
		
		
			if (!isset($ok) || !$ok){
				$error .= "An unknown error occured! ;";
			}
		}
		
		
		
		return new JSONResponse(array('error' => $error,
									'return' => isset($ok)));
	
	}
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function setMessageRead(){

		$msg_id = $this->params("msg_id");
		$msg = new MessageRepository();
		$msgs = $msg->setMessageRead($msg_id);
		$error = "";

		return new JSONResponse(array('error' => $error,
									'return' => isset($msgs)));
	}
	
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function setMessageDeletedOwner(){
	
		$msg_id = $this->params("msg_id");
		$msg = new MessageRepository();
		$msgs = $msg->setMessageDeletedFrom($msg_id);
		$error = "";
	
		return new JSONResponse(array('error' => $error,
				'return' => isset($msgs)));
	}
	
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function setMessageDeletedTo(){
	
		$msg_id = $this->params("msg_id");
		$msg = new MessageRepository();
		$msgs = $msg->setMessageDeletedTo($msg_id);
		
		
		$error = "";
		
		if ($msgs == false){
			$error = "could not delete Message! (ID: $msg_id)";
		}
		
		return new JSONResponse(array('error' => $error,
				'return' => isset($msgs)));
	}
	
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
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
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
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
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
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