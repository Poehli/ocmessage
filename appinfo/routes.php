<?php

namespace OCA\OCMessage;

use \OCA\AppFramework\App;
use \OCA\OCMessage\DependencyInjection\DIContainer;

$this->create('ocmessage_index', '/')->action(
    function($params){
        // call the index method on the class PageController
        App::main('PageController', 'index', $params, new DIContainer());
    }
);

$this->create('ocmessage_getMessages', '/message/getMessages')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'getMessages', $params, new DIContainer());
}
);
$this->create('ocmessage_getSendMessages', '/message/getSendMessage')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'getSendMessages', $params, new DIContainer());
}
);
$this->create('ocmessage_getUnreadMessages', '/message/getUnreadMessage')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'getUnreadMessages', $params, new DIContainer());
}
);
$this->create('ocmessage_sendMessage', '/message/sendMessage')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'sendMessage', $params, new DIContainer());
}
);
$this->create('ocmessage_setMessageRead', '/message/setMessageRead')->action(
		function($params){
	// call the index method on the class PageController
	App::main('PageController', 'setMessageRead', $params, new DIContainer());
}
);
$this->create('ocmessage_setAllMessagesRead', '/message/setAllMessagesRead')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'setAllMessagesRead', $params, new DIContainer());
}
);
$this->create('ocmessage_hasUnreadMessage', '/message/hasUnreadMessage')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'hasUnreadMessage', $params, new DIContainer());
}
);
$this->create('ocmessage_unreadMessageCount', '/message/unreadMessageCount')->action(
		function($params){
	// call the index method on the class PageController
	App::main('MessageController', 'unreadMessageCount', $params, new DIContainer());
}
);

