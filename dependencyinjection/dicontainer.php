<?php

namespace OCA\OCMessage\DependencyInjection;

use \OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use \OCA\OCMessage\Controller\PageController;
use \OCA\OCMessage\Controller\MessageController;
use \OCA\OCMessage\Db\MessageRepository;

class DIContainer extends BaseContainer {

    public function __construct(){
        parent::__construct('ocmessage');

        // use this to specify the template directory
       // $this['TwigTemplateDirectory'] = __DIR__ . '/../templates';

        $this['PageController'] = $this->share(function($c){
            return new PageController($c['API'], $c['Request']);
        });
        
        $this['MessageRepository'] = $this->share(function($c){
        	return new MessageRepository();
        });
        
        $this['MessageController'] = $this->share(function($c){
        	return new MessageController($c["API"], $c['Request']);
        });
    }

}

