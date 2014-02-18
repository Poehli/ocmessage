<?php 

$splitsign = array(";", ",");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$now = new DateTime();
$now = $now->getTimestamp();

$users = array($request->msg_to);
echo "users: ".$users[0]."\n";
for ($i = 0; $i < sizeof($splitsign); $i++){
	$tmpuser = array();
	foreach ($users as $user){
		$users_split = explode($splitsign[$i],$user);
		$tmpuser = array_merge($tmpuser, $users_split);
		echo "\n user: $user - userslength: ".sizeof($users)." - ".sizeof($tmpuser);
	}
	$users = $tmpuser;
}

echo "\nuserslength: ".sizeof($users);

$query = \OCP\DB::prepare("INSERT INTO *PREFIX*msg (message_owner, message_to, message_timestamp, message_content, message_subject) 
											VALUES (?, ?, ?, ?, ?)");
foreach ($users as $user){
	$user = trim($user);
	if ($user == ""){continue;}
	
	if (! \OCP\User::userExists($user)){
		echo "User does not exist: $user";
		continue;
	}
	
	
	$message = new \OCA\test\Controller\MessagesQuery();
	$ok = $message->sendMessage($user, $request->msg_subject, $request->msg_content);


if (!isset($ok) || !$ok){
	echo "An unknown error occured!";
}
}

?>