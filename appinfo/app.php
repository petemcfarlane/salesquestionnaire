<?php
namespace OCA\SalesQuestionnaire;

if ( !\OCP\App::isEnabled('appframework') ) {
	 \OCP\Util::writeLog('salesquestionnaire', "App Framework app must be enabled", \OCP\Util::ERROR); 
	 exit;
}

\OCP\Share::registerBackend('salesquestionnaire', '\OCA\SalesQuestionnaire\Lib\Share\ShareQuestionnaire');
\OC_Search::registerProvider('\OCA\SalesQuestionnaire\Lib\SearchProvider');

$api = new \OCA\AppFramework\Core\API('salesquestionnaire');

$api->addNavigationEntry(array(
	'id' => $api->getAppName(),
	'order' => 10,
	'href' => $api->linkToRoute('salesquestionnaire.questionnaire.index'),
	'icon' => $api->imagePath('sales_questionnaire.svg'),
	'name' => $api->getTrans()->t('Sales Questionnaire')
)); 