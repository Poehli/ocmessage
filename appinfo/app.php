<?php

use \OCA\OCMessage\Controller\PageController;

use \OCA\OCMessage\DependencyInjection\DIContainer;

// dont break owncloud when the appframework is not enabled
if(\OCP\App::isEnabled('appframework')){
	
    $container = new DIContainer();
    $api = $container['API'];

    $logo = $name =  "";
    if ($container['MessageRepository']->hasUnreadMessage()){
    	$logo = "new_msg.png";
    	$name = "Neu (".$container['MessageRepository']->unreadMessageCount().")";
    } else {
    	$logo = "no_new_msg.png";
    	$name = "Nachrichten";
    }
    
    $api->addNavigationEntry(array(

      // the string under which your app will be referenced in owncloud
      'id' => $api->getAppName(),

      // sorting weight for the navigation. The higher the number, the higher
      // will it be listed in the navigation
      'order' => 10,

      // the route that will be shown on startup
      'href' => $api->linkToRoute('ocmessage_index'),

      // the icon that will be shown in the navigation
      // this file needs to exist in img/example.png
      'icon' => $api->imagePath($logo),

      // the title of your application. This will be used in the
      // navigation or on the settings page of your app
      'name' => $name

    ));
} else {
  $msg = 'Can not enable the test app because the App Framework App is disabled';
  \OCP\Util::writeLog('test', $msg, \OCP\Util::ERROR);
}
