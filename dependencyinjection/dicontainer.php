<?php

namespace OCA\SalesQuestionnaire\DependencyInjection;

use \OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use \OCA\SalesQuestionnaire\Controller\QuestionnaireController;

class DIContainer extends BaseContainer {

    public function __construct(){
        parent::__construct('salesquestionnaire');
		
        // use this to specify the template directory
        $this['TwigTemplateDirectory'] = __DIR__ . '/../templates';

        $this['QuestionnaireController'] = function($c){
            return new QuestionnaireController($c['API'], $c['Request']);
        };
    }

}