<?php
namespace OCA\SalesQuestionnaire\Controller;

use \OCA\AppFramework\Http\Request;
use \OCA\AppFramework\Http\JSONResponse;
use \Exception;
use \OCA\SalesQuestionnaire\Db\Questionnaire;
use \OCA\SalesQuestionnaire\Db\QuestionnaireMapper;
use \OCA\AppFramework\Http\TemplateResponse;
use \OCA\AppFramework\Utility\ControllerTestUtility;
require_once(__DIR__ . "/../classloader.php");

class QuestionnaireControllerTest extends ControllerTestUtility {

	private $api;
	private $request;
	private $controller;

	/**
	 * Gets run before each test
	 */
	public function setUp(){
		$this->api = $this->getAPIMock();
		$this->request = new Request();
		$this->controller = new QuestionnaireController($this->api, $this->request);
		$this->user = "Pete";
	}


	public function testQuestionnaireControllerConstruct(){
		$questionnaireMapper = new QuestionnaireMapper($this->api);
		$this->assertEquals($questionnaireMapper, $this->controller->questionnaireMapper);
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

	public function testRedirect() {
		$this->assertTrue($this->controller->redirect() instanceof TemplateResponse);
	}

	public function testSortByUpdatedAt() {

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

	//public function testIndex(){
		//$this->api->expects($this->once())
		//	->method('getUserId')
		//	->will($this->returnValue($this->user));
		//$response = $this->controller->index();
		//$this->assertTrue($response instanceof TemplateResponse);
		//$this->assertEquals('main', $response->getTemplateName());
	//}


    /*public function __construct($api, $request){
        parent::__construct($api, $request);
		$this->questionnaireMapper = new QuestionnaireMapper($this->api);
		$this->api->addStyle('salesquestionnaire');
		$this->api->addScript('salesquestionnaire');
		$this->api->addScript('3rdparty/jquery.pjax');
		$this->renderas = isset($_SERVER['HTTP_X_PJAX']) ? '' : 'user';
    }


	protected function formatDate($date) {
		$pattern1 = "/\d{4}-\d{2}-\d{2}/"; // yyyy-mm-dd
		$pattern2 = "/(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2,4})/"; // dd/mm/yy or dd.mm.yy(yy) or dd-mm-yy(yy)
		if (preg_match($pattern1, $date, $match1) ) {
			return date("Y-m-d H:i:s", strtotime($date));
		} elseif (preg_match($pattern2, $date, $match2) ) {
			return date("Y-m-d H:i:s", mktime(0,0,0,$match2[2],$match2[1],$match2[3]));
		} elseif ($date==""){
			return NULL;
		} else {
			throw new Exception("Error with date, format must be dd/mm/yyyy");
		}
	}
	
	protected function questionnaireFromRequest($request) {
		$questionnaire = new Questionnaire;
		$questionnaire->setcustomer($request->customer);
		$questionnaire->setcustomerAddress($request->customerAddress);
		$questionnaire->setcustomerWebsite($request->customerWebsite);
		$questionnaire->setprojectName($request->projectName);
		$questionnaire->setprojectType($request->projectType);
		$questionnaire->setplatform($request->platform);
		$questionnaire->setmeetingWith($request->meetingWith);
		$questionnaire->setmeetingDate(self::formatDate($request->meetingDate));
		$questionnaire->setrepresentative($request->representative);
		$questionnaire->settechnicalAuthority($request->technicalAuthority);
		$questionnaire->setcommercialAuthority($request->commercialAuthority);
		$questionnaire->settechnicalDiscussion($request->technicalDiscussion);
		$questionnaire->setcommercialDiscussion($request->commercialDiscussion);
		$questionnaire->setpresentQuotation($request->presentQuotation);
		$questionnaire->setnegotiateOrder($request->negotiateOrder);
		$questionnaire->settechnicalAmbition($request->technicalAmbition);
		$questionnaire->setotherRequirements($request->otherRequirements);
		$questionnaire->setcommercialDrives($request->commercialDrives);
		$questionnaire->setnotes($request->notes);
		$questionnaire->setpurchaseDecision(self::formatDate($request->purchaseDecision));
		$questionnaire->setsupplyEvaluation(self::formatDate($request->supplyEvaluation));
		$questionnaire->setoptimizeBy(self::formatDate($request->optimizeBy));
		$questionnaire->setmanufactureDate(self::formatDate($request->manufactureDate));
		$questionnaire->setretailDate(self::formatDate($request->retailDate));
		$questionnaire->setminimumOrder($request->minimumOrder);
		$questionnaire->setrampYear1($request->rampYear1);
		$questionnaire->setrampYear2($request->rampYear2);
		$questionnaire->setrampYear3($request->rampYear3);
		$questionnaire->setterritories($request->territories);
		$questionnaire->setretailers($request->retailers);
		$questionnaire->setbom($request->bom);
		$questionnaire->setrrp($request->rrp);
		$questionnaire->setlicenseFee($request->licenseFee);
		$questionnaire->setbudgeted($request->budgeted);
		$questionnaire->setoem($request->oem);
		$questionnaire->setconvince($request->convince);
		$questionnaire->settasks($request->tasks);
		$questionnaire->setgeneralNotes($request->generalNotes);
		$questionnaire->setriskAssessment($request->riskAssessment);
		return $questionnaire;
	}
	
	
	protected function redirect($url='salesquestionnaire.questionnaire.index', $params=array()) {
		$response = new TemplateResponse($this->api, "index");
		$response->addHeader('Location', $this->api->linkToRoute($url, $params) );
		return $response;
	}
	
	public function sortByUpdatedAt($questionnaires){
		usort($questionnaires, function($a, $b){
			$a = (array)$a;
			$b = (array)$b;
			if($a['updatedAt']==$b['updatedAt']) return 0;
		    return $a['updatedAt'] < $b['updatedAt']?1:-1;
		});
		return $questionnaires;
	}

    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    /*public function index(){
		$userQuestionnaires = $this->questionnaireMapper->findUserQuestionnaires( $this->api->getUserId() );
		$questionnaires = array_merge($userQuestionnaires, \OCP\Share::getItemsSharedWith('salesquestionnaire', 0) );
		$params['response'] = $this->request->response;
		$params['questionnaires'] = self::sortByUpdatedAt($questionnaires);
		return $this->render('index', $params, $this->renderas);
    }
	
    /**
     * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
     */
    /*public function newForm(){
        return $this->render('new', array(), $this->renderas);
    }


    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
   /*public function create(){
		try {
			$questionnaire = self::questionnaireFromRequest($this->request);
			if ( empty($this->request->customer) ) throw new Exception("Customer must be set", 1);
			$questionnaire->setCreatedAt(date("Y-m-d H:i:s"));
			$questionnaire->setUid($this->api->getUserId());
			$questionnaire = $this->questionnaireMapper->insert($questionnaire);
			return $this->redirect( 'salesquestionnaire.questionnaire.show', array( "Id" => $questionnaire->getId(), "response" => array("status"=>"success", "message"=>"New sales questionnaire successfully added") ) );
		} catch (Exception $exception) {
			$params['questionnaire'] = $questionnaire;
			$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('new', $params, $this->renderas);
		}
    }


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    /*public function show(){
		try {
			$questionnaire = $this->questionnaireMapper->findByIdAndUser( $this->request->Id, $this->api->getUserId() );
			$params['response'] = $this->request->response;
			$params['questionnaire'] = $questionnaire;
	        return $this->render('show', $params, $this->renderas);
		} catch (Exception $exception) {
			if ($exception->getMessage() == 'No matching entry found') {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_READ) ) 
						throw new Exception("You don't have permissions to see this sales questionnaire");
					$questionnaire = (array)$this->questionnaireMapper->findById( $shared['item_target'] );
					if ($shared['permissions'] & \OCP\PERMISSION_CREATE) $questionnaire['permissions'][] = "CREATE";
					if ($shared['permissions'] & \OCP\PERMISSION_READ)   $questionnaire['permissions'][] = "READ";
					if ($shared['permissions'] & \OCP\PERMISSION_UPDATE) $questionnaire['permissions'][] = "UPDATE";
					if ($shared['permissions'] & \OCP\PERMISSION_DELETE) $questionnaire['permissions'][] = "DELETE";
					if ($shared['permissions'] & \OCP\PERMISSION_SHARE)  $questionnaire['permissions'][] = "SHARE";
					$params['response'] = $this->request->response;
					$params['questionnaire'] = $questionnaire;
			        return $this->render('show', $params, $this->renderas);
				} catch (Exception $exception) {
					$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $params, $this->renderas);
				}
			}
			$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('error', $params, $this->renderas);
		}
	}


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    /*public function edit(){
		try {
			$params['questionnaire'] = $this->questionnaireMapper->findByIdAndUser($this->request->Id, $this->api->getUserId() );
	        return $this->render('edit', $params, $this->renderas);
		} catch (Exception $exception) {
			if ($exception->getMessage() == 'No matching entry found' ){
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_UPDATE) ) 
						throw new Exception("You don't have permissions to edit this sales questionnaire");
					$params['questionnaire'] = $this->questionnaireMapper->findById($shared['item_target']);
			        return $this->render('edit', $params, $this->renderas);
				} catch (Exception $exception) {
					$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $params, $this->renderas);					
				}
			}
			$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('error', $params, $this->renderas);
		}
    }


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    /*public function update(){
		try {
			$questionnaire = $this->questionnaireMapper->findByIdAndUser( $this->request->Id, $this->api->getUserId() );
			$questionnaire = self::questionnaireFromRequest($this->request);
			if ( empty($this->request->customer) ) throw new Exception("Customer must be set", 1);
			$questionnaire->setId($this->request->Id);
			$questionnaire->setUpdatedAt(date("Y-m-d H:i:s"));
			$questionnaire->setModifiedBy($this->api->getUserId());
			$this->questionnaireMapper->update($questionnaire);
			return $this->redirect( 'salesquestionnaire.questionnaire.show', array( "Id" => $questionnaire->getId(), "response"=>array("status"=>"success", "message"=>"Susessfully updated sales questionnaire.") ) );
		} catch (Exception $exception) {
			if ($exception->getMessage() == "No matching entry found") {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_UPDATE) ) 
						throw new Exception("You don't have permissions to edit this sales questionnaire");
					$questionnaire = self::questionnaireFromRequest($this->request);
					if ( empty($this->request->customer) ) throw new Exception("Customer must be set", 1);
					$questionnaire->setId($this->request->Id);
					$questionnaire->setUpdatedAt(date("Y-m-d H:i:s"));
					$questionnaire->setModifiedBy($this->api->getUserId());
					$this->questionnaireMapper->update($questionnaire);
					return $this->redirect( 'salesquestionnaire.questionnaire.show', array( "Id" => $questionnaire->getId(), "response"=>array("status"=>"success", "message"=>"Susessfully updated sales questionnaire.") ) );
				} catch (Exception $exception) {
					$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $params, $this->renderas);					
				}
			}
			$params['questionnaire'] = $questionnaire;
 			$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('edit', $params, $this->renderas);
		}
    }


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    /*public function delete(){
		try {
			$questionnaire = $this->questionnaireMapper->findByIdAndUser($this->request->Id, $this->api->getUserId() );
			$params['questionnaire'] = $questionnaire;
	        return $this->render('delete', $params, $this->renderas);
		} catch (Exception $exception) {
			if ($exception->getMessage = "No matching entry found") {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_DELETE) ) 
						throw new Exception("You don't have permissions to delete this sales questionnaire");
					$questionnaire = (array)$this->questionnaireMapper->findById( $shared['item_target'] );
					$params['questionnaire'] = $questionnaire;
			        return $this->render('delete', $params, $this->renderas);				
				} catch (Exception $exception) {
					$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $params, $this->renderas);				
				}
			}
			$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('delete', $params, $this->renderas);
		}
	}


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    /*public function destroy(){
		try {
			//$questionnaire = new Questionnaire();
			//$questionnaire->setId($this->request->Id);
			$questionnaire = $this->questionnaireMapper->findByIdAndUser($this->request->Id, $this->api->getUserId() );
			$this->questionnaireMapper->delete($questionnaire);
			return $this->redirect( 'salesquestionnaire.questionnaire.index', array("response" => array( "status"=>"success", "message"=>"Sales questionnaire successfully deleted" ) ) );
		} catch (Exception $exception) {
			if ($exception->getMessage() == "No matching entry found") {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_DELETE) ) 
						throw new Exception("You don't have permissions to delete this sales questionnaire");
					$questionnaire = $this->questionnaireMapper->findById( $shared['item_target'] );
					$this->questionnaireMapper->delete($questionnaire);
					return $this->redirect( 'salesquestionnaire.questionnaire.index', array("response" => array( "status"=>"success", "message"=>"Sales questionnaire successfully deleted" ) ) );
				} catch (Exception $exception) {
					$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $params, $this->renderas);				
				}
			}
			$params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('delete', $params, $this->renderas);
		}
    }
}
		*/
}