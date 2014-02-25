<?php
 
namespace OCA\OCMessage\Db;

use \OCP\User;
use \OCP\DB;

class MessageRepository {
	private $userId;
	public function __construct($user=""){
		if ($user == ""){
			$this->userId = User::getUser();
		} else {
			$this->userId = $user;
		}
	}
	
	public function getMessages(){
		$query = DB::prepare("SELECT * FROM *PREFIX*msg WHERE message_to=? AND message_delto=?");
		return $query->execute(array($this->userId, 0))->fetchAll();
	}
	
	public function getSendMessages(){
		$query = DB::prepare("SELECT * FROM *PREFIX*msg WHERE message_owner=? AND message_delfrom=?");
		return $query->execute(array($this->userId, 0))->fetchAll();
	}
	
	public function getUnreadMessages(){
		$query = DB::prepare("SELECT * FROM *PREFIX*msg WHERE message_to=? AND message_read=? AND message_delto=?");
		return $query->execute(array($this->userId, "0", 0))->fetchAll();
	}
	
	
	
	public function sendMessage($msg_to, $msg_subject, $msg_content, $msg_timestamp=0 ){
		if ($msg_timestamp==0){
			$now = new \DateTime();
			$msg_timestamp = $now->getTimestamp();
		}
		$query = DB::prepare("INSERT INTO *PREFIX*msg (message_owner, message_to, message_timestamp, message_content, message_subject)
				VALUES (?, ?, ?, ?, ?)");
		return $query->execute(
				array(
						$this->userId,
						$msg_to,
						$msg_timestamp,
						$msg_content,
						$msg_subject,
						));
	}
	
	
	public function setMessageRead($msg_id){
		$query = DB::prepare("UPDATE *PREFIX*msg SET message_read=? WHERE message_id=?");
		return $query->execute(array("1",$msg_id));
	}
	
	public function setMessageDeletedTo($msg_id){
		$query = DB::prepare("UPDATE *PREFIX*msg SET message_delto=? WHERE message_id=?");
		return $query->execute(array("1",$msg_id));
	}
	
	public function setMessageDeletedFrom($msg_id){
		$query = DB::prepare("UPDATE *PREFIX*msg SET message_delfrom=? WHERE message_id=?");
		return $query->execute(array("1",$msg_id));
	}
	
	public function setAllMessagesRead(){
		$query = DB::prepare("UPDATE *PREFIX*msg SET message_read=? WHERE message_to=?");
		return $query->execute(array("1",$this->userId));
	}
	
	public function hasUnreadMessage(){
		$query = DB::prepare("SELECT * FROM *PREFIX*msg WHERE message_to=? AND message_read=?");
		return (sizeof($query->execute(array($this->userId, "0"))->fetchAll()) > 0);
	}
	
	public function unreadMessageCount(){
		$query = DB::prepare("SELECT * FROM *PREFIX*msg WHERE message_to=? AND message_read=?");
		
		
		return sizeof($query->execute(array($this->userId, 0))->fetchAll());
	}
}

?>