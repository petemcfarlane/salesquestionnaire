<?php
namespace OCA\SalesQuestionnaire\Controller;

use \Exception;
use \OCA\AppFramework\Http\Request;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http\TemplateResponse;
use \OCA\AppFramework\Http\RedirectResponse;
use \OCA\SalesQuestionnaire\Db\Questionnaire;
use \OCA\SalesQuestionnaire\Db\QuestionnaireMapper;
use \OCA\AppFramework\Utility\ControllerTestUtility;
require_once(__DIR__ . "/../classloader.php");

class QuestionnaireControllerTest extends ControllerTestUtility {

	private $api;
	private $request;
	private $controller;
	private $questionnaireMapper;

	public function setUp(){
		$this->api = $this->getAPIMock();
		$this->request = new Request();
		

		$this->questionnaireMapper = $this->getMock('QuestionnaireMapper', array('getUserQuestionnaires'));
		$this->questionnaireMapper->expects($this->any())
							->method('getUserQuestionnaires')
							->will($this->returnValue(array('id'=>1)));
		$this->controller = new QuestionnaireController($this->api, $this->request, $this->questionnaireMapper);
	}

	public function testQuestionnaireControllerConstruct(){
		// $this->assertTrue($this->controller->questionnaireMapper instanceof QuestionnaireMapper);
		$this->assertEquals('user', $this->controller->renderas);
		$_SERVER['HTTP_X_PJAX'] = 'true';
		$pjaxQuestionnaire = new QuestionnaireController($this->api, $this->request);
		$this->assertEquals('', $pjaxQuestionnaire->renderas);
		$this->assertTrue(isset($this->controller->params['requesttoken']));
	}

	public function testFormatDate() {
		$date1 = "19/05/1989";
		$date2 = "19/5/1989";
		$date3 = "19/05/89";
		$date4 = "19/5/89";
		$date5 = "19.05.1989";
		$date6 = "19.5.1989";
		$date7 = "19.05.89";
		$date8 = "19.5.89";
		$date9 = "19-05-1989";
		$date10 = "19-5-1989";
		$date11 = "19-05-89";
		$date12 = "19-5-89";
		$date13 = "1989-05-19";
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date1));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date2));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date3));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date4));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date5));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date6));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date7));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date8));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date9));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date10));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date11));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date12));
		$this->assertEquals("1989-05-19 00:00:00", $this->controller->formatDate($date13));
	}

	public function testQuestionnaireFromRequest() {
		$request = new Request(array('post' => array(
			'requesttoken' => '20b11d69dd8cb268508f',
			'customer' => 'FooBar',
			'customerWebsite' => 'http://foobar.com',
			'customerAddress' => '1 Foo, Bar lane, XYZ',
			'projectName' => 'BazQux',
			'projectType' => 'Qux type',
			'platform' => 'Bax platform',
			'meetingDate' => '1/1/2013',
			'meetingLocation' => 'FooLand',
			'representative' => 'Foobody',
			'technicalAuthority' => 'Barry',
			'commercialAuthority' => 'Baz',
			'technicalRequirements' => 'Foo, Bar',
			'commercialRequirements' => 'Baz, Qux',
			'otherRequirements' => 'Food',
			'meetingNotes' => 'foofoofoo',
			'purchaseDecision' => '2013-01-01',
			'supplyEvaluation' => '2013-01-01',
			'optimizeBy' => '2013-01-01',
			'manufactureDate' => '2013-01-01',
			'retailDate' => '2013-01-01',
			'minimumOrder' => '1',
			'rampYear1' => '2',
			'rampYear2' => '',
			'rampYear3' => '4',
			'territories' => 'World',
			'retailers' => 'Shops',
			'bom' => '£99',
			'rrp' => '£999',
			'licenseFee' => '£9',
			'oem' => 'Foob',
			'convince' => 'Barb',
			'tasks' => 'foo to bar',
			'generalNotes' => 'baz baz',
			'riskAssessment' => 'qux qux'
		)));
		
		$questionnaire = $this->controller->questionnaireFromRequest($request);
		$this->assertTrue($questionnaire instanceof Questionnaire);
		$this->assertEquals($questionnaire->getCustomer(), 'FooBar');
		$this->assertEquals($questionnaire->getCustomerWebsite(), 'http://foobar.com');
		$this->assertEquals($questionnaire->getCustomerAddress(), '1 Foo, Bar lane, XYZ');
		$this->assertEquals($questionnaire->getProjectName(), 'BazQux');
		$this->assertEquals($questionnaire->getProjectType(), 'Qux type');
		$this->assertEquals($questionnaire->getPlatform(), 'Bax platform');
		$this->assertEquals($questionnaire->getMeetingDate(), '2013-01-01 00:00:00');
		$this->assertEquals($questionnaire->getMeetingLocation(), 'FooLand');
		$this->assertEquals($questionnaire->getRepresentative(), 'Foobody');
		$this->assertEquals($questionnaire->getMeetingPurpose(), null);
		$this->assertEquals($questionnaire->getTechnicalAuthority(), 'Barry');
		$this->assertEquals($questionnaire->getCommercialAuthority(), 'Baz');
		$this->assertEquals($questionnaire->getTechnicalRequirements(), 'Foo, Bar');
		$this->assertEquals($questionnaire->getCommercialRequirements(), 'Baz, Qux');
		$this->assertEquals($questionnaire->getOtherRequirements(), 'Food');
		$this->assertEquals($questionnaire->getMeetingNotes(), 'foofoofoo');
		$this->assertEquals($questionnaire->getPurchaseDecision(), '2013-01-01 00:00:00');
		$this->assertEquals($questionnaire->getSupplyEvaluation(), '2013-01-01 00:00:00');
		$this->assertEquals($questionnaire->getOptimizeBy(), '2013-01-01 00:00:00');
		$this->assertEquals($questionnaire->getManufactureDate(), '2013-01-01 00:00:00');
		$this->assertEquals($questionnaire->getRetailDate(), '2013-01-01 00:00:00');
		$this->assertEquals($questionnaire->getMinimumOrder(), '1');
		$this->assertEquals($questionnaire->getRampYear1(), '2');
		$this->assertEquals($questionnaire->getRampYear2(), null);
		$this->assertEquals($questionnaire->getRampYear3(), '4');
		$this->assertEquals($questionnaire->getTerritories(), 'World');
		$this->assertEquals($questionnaire->getRetailers(), 'Shops');
		$this->assertEquals($questionnaire->getBom(), '£99');
		$this->assertEquals($questionnaire->getRrp(), '£999');
		$this->assertEquals($questionnaire->getLicenseFee(), '£9');
		$this->assertEquals($questionnaire->getOem(), 'Foob');
		$this->assertEquals($questionnaire->getConvince(), 'Barb');
		$this->assertEquals($questionnaire->getTasks(), 'foo to bar');
		$this->assertEquals($questionnaire->getGeneralNotes(), 'baz baz');
		$this->assertEquals($questionnaire->getRiskAssessment(), 'qux qux');
	}

	public function testRedirect() {
		$this->assertTrue($this->controller->redirect() instanceof RedirectResponse);
		$url = 'salesquestionnaire.questionnaire.index';
		$this->api->expects($this->once())
				  ->method('linkToRoute')
				  ->will($this->returnValue($url));

		$redirect = $this->controller->redirect($url);
		$this->assertEquals($url, $redirect->getRedirectUrl());
	}

	public function testCmp() {
		$questionnaires = array(
			array('customer' => 'aaa', 'updatedAt' => '2013-08-27 12:00:00'),
			array('customer' => 'bbb', 'updatedAt' => '2013-08-25 12:00:00'),
			array('customer' => 'ccc', 'updatedAt' => '2013-08-26 12:00:00')
		);
		
		$this->assertEquals('-1', $this->controller->cmp($questionnaires[0], $questionnaires[1]) );
		$this->assertEquals('1', $this->controller->cmp($questionnaires[1], $questionnaires[2]) );
	}
	
	public function testIndexLoads() {
		$response = $this->controller->index();
		var_dump($response);
		$this->assertTrue($response instanceof TemplateResponse);
		$this->assertEquals('index', $response->getTemplateName() );
	}

	public function testIndex() {
		// $stub = $this->getMock('QuestionnaireMapper');
		// $stub->expects($this->any())
			 // ->method('getUserQuestionnaires')
			 // ->will($this->returnValue(true));
// //		$this->controller->questionnaireMapper = $this->getMock('QuestionnaireMapper');
// //		$this->controller->questionnaireMapper->expects($this->any())
// //											  ->method('getUserQuestionnaires');
		// $this->controller->questionnaireMapper = $stub;
		// $response = $this->controller->index();
		// // var_dump($stub->getUserQuestionnaires());
/*
		try {
	    	if (isset($this->request->search)) {
    			$userQuestionnaires = $this->questionnaireMapper->searchUserQuestionnaires( $this->api->getUserId(), $this->request->search );
				$questionnaires = array_merge($userQuestionnaires, \OCP\Share::getItemsSharedWith('salesquestionnaire', 0, array('search'=>$this->request->search)) );
			} else {
				$userQuestionnaires = $this->questionnaireMapper->getUserQuestionnaires( $this->api->getUserId() );
				$questionnaires = array_merge($userQuestionnaires, \OCP\Share::getItemsSharedWith('salesquestionnaire', 0) );
			}
			usort($questionnaires, array("self", "cmp") );
	    	$this->params = array_merge($this->params, array(
	    		'sortby'=>$this->request->sortby,
	    		'direction'=>$this->request->direction,
	    		'search'=>$this->request->search,
				'questionnaires'=>$questionnaires,
				'response'=>$this->request->response
			));
			return $this->render('index', $this->params, $this->renderas, array('X-PJAX-URL'=>$this->api->linkToRoute('salesquestionnaire.questionnaire.index') ) );
		} catch (Exception $exception) {
			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('error', $this->params, $this->renderas);
		}
*/
	}

	public function testAnnotations(){
		$annotations = array('IsAdminExemption', 'IsSubAdminExemption', 'CSRFExemption');
		$this->assertAnnotations($this->controller, 'index', $annotations);
		$this->assertAnnotations($this->controller, 'newForm', $annotations);
		$this->assertAnnotations($this->controller, 'show', $annotations);
		$this->assertAnnotations($this->controller, 'edit', $annotations);
		$this->assertAnnotations($this->controller, 'delete', $annotations);
		
		$annotations = array('IsAdminExemption', 'IsSubAdminExemption');
		$this->assertAnnotations($this->controller, 'create', $annotations);
		$this->assertAnnotations($this->controller, 'update', $annotations);
		$this->assertAnnotations($this->controller, 'destroy', $annotations);
	}

	public function testNewForm() {
		$response = $this->controller->newForm();
		$this->assertTrue($response instanceof TemplateResponse);
		$this->assertEquals('new', $response->getTemplateName() );
	}

}