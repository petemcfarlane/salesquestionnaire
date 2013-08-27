<?php

namespace OCA\SalesQuestionnaire\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\JSONResponse;
use \Exception;
use \OCA\SalesQuestionnaire\Db\Questionnaire;
use \OCA\SalesQuestionnaire\Db\QuestionnaireMapper;
use \OCA\AppFramework\Http\TemplateResponse;

class QuestionnaireController extends Controller {


    public function __construct($api, $request){
        parent::__construct($api, $request);
		$this->questionnaireMapper = new QuestionnaireMapper($this->api);
		$this->api->addStyle('salesquestionnaire');
		$this->api->addScript('salesquestionnaire');
		$this->api->addScript('3rdparty/jquery.pjax');
		$this->renderas = isset($_SERVER['HTTP_X_PJAX']) ? '' : 'user';
		$this->params = array('requesttoken' => \OC_Util::callRegister() );
    }


	public function formatDate($date) {
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
		$questionnaire->setCustomer($request->customer);
		$questionnaire->setCustomerAddress($request->customerAddress);
		$questionnaire->setCustomerWebsite($request->customerWebsite);
		$questionnaire->setProjectName($request->projectName);
		$questionnaire->setProjectType($request->projectType);
		$questionnaire->setPlatform($request->platform);
		if (isset($request->meetingWith) ) $questionnaire->setMeetingWith(implode(',', array_values(array_filter($request->meetingWith))));
		$questionnaire->setMeetingDate(self::formatDate($request->meetingDate));
		$questionnaire->setMeetingLocation($request->meetingLocation);
		$questionnaire->setRepresentative($request->representative);
		$questionnaire->setMeetingPurpose(empty($request->meetingPurpose) ? NULL : implode(', ', $request->meetingPurpose));
		$questionnaire->setTechnicalAuthority($request->technicalAuthority);
		$questionnaire->setCommercialAuthority($request->commercialAuthority);
		$questionnaire->setTechnicalRequirements($request->technicalRequirements);
		$questionnaire->setCommercialRequirements($request->commercialRequirements);
		$questionnaire->setOtherRequirements($request->otherRequirements);
		$questionnaire->setMeetingNotes($request->meetingNotes);
		$questionnaire->setPurchaseDecision(self::formatDate($request->purchaseDecision));
		$questionnaire->setSupplyEvaluation(self::formatDate($request->supplyEvaluation));
		$questionnaire->setOptimizeBy(self::formatDate($request->optimizeBy));
		$questionnaire->setManufactureDate(self::formatDate($request->manufactureDate));
		$questionnaire->setRetailDate(self::formatDate($request->retailDate));
		$questionnaire->setMinimumOrder( $request->minimumOrder ? $request->minimumOrder : null );
		$questionnaire->setRampYear1( $request->rampYear1 ? $request->rampYear1 : null );
		$questionnaire->setRampYear2( $request->rampYear2 ? $request->rampYear2 : null );
		$questionnaire->setRampYear3( $request->rampYear3 ? $request->rampYear3 : null );
		$questionnaire->setTerritories($request->territories);
		$questionnaire->setRetailers($request->retailers);
		$questionnaire->setBom($request->bom);
		$questionnaire->setRrp($request->rrp);
		$questionnaire->setLicenseFee($request->licenseFee);
		$questionnaire->setBudgeted($request->budgeted);
		$questionnaire->setOem($request->oem);
		$questionnaire->setConvince($request->convince);
		$questionnaire->setTasks($request->tasks);
		$questionnaire->setGeneralNotes($request->generalNotes);
		$questionnaire->setRiskAssessment($request->riskAssessment);
		return $questionnaire;
	}
	
	
	public function redirect($url='salesquestionnaire.questionnaire.index', $args=array()) {
		$response = new TemplateResponse($this->api, "index");
		$response->addHeader('Location', $this->api->linkToRoute($url, $args) );
		return $response;
	}
	

	public function cmp ($a, $b) {
		$a = (array)$a;
		$b = (array)$b;
		$sortby = $this->request->sortby ? $this->request->sortby : "updatedAt";
		if ($a[$sortby] == $b[$sortby]) return 0;
		if ($this->request->direction == "desc") return $a[$sortby] > $b[$sortby] ? 1 : -1;
		return ($a[$sortby] < $b[$sortby]) ? 1 : -1;
	}


   /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function index(){
    	if (isset($this->request->search)) {
    		try {
    			$userQuestionnaires = $this->questionnaireMapper->searchUserQuestionnaires( $this->api->getUserId(), $this->request->search );
				$this->params['response'] = $this->request->response;
				$this->params['questionnaires'] = $userQuestionnaires;
				$this->params['sortby'] = $this->request->sortby;
				$this->params['direction'] = $this->request->direction;
				$this->params['search'] = $this->request->search;
				return $this->render('index', $this->params, $this->renderas);
    		} catch (Exception $exception) {
    			var_dump ($exception);
			}
		}

		$userQuestionnaires = $this->questionnaireMapper->findUserQuestionnaires( $this->api->getUserId() );
		$questionnaires = array_merge($userQuestionnaires, \OCP\Share::getItemsSharedWith('salesquestionnaire', 0) );
		usort($questionnaires, array("self", "cmp") );
		$this->params['questionnaires'] = $questionnaires;
		$this->params['response'] = $this->request->response;
		$this->params['sortby'] = $this->request->sortby;
		$this->params['direction'] = $this->request->direction;
		return $this->render('index', $this->params, $this->renderas, array('X-PJAX-URL'=>$this->api->linkToRoute('salesquestionnaire.questionnaire.index') ) );
    }
	
    /**
     * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
     */
    public function newForm(){
        return $this->render('new', $this->params, $this->renderas);
    }


    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function create(){
		try {
			$questionnaire = self::questionnaireFromRequest($this->request);
			if ( empty($this->request->customer) ) throw new Exception("Customer must be set", 1);
			$questionnaire->setCreatedAt(date("Y-m-d H:i:s"));
			$questionnaire->setUid($this->api->getUserId());
			$questionnaire = $this->questionnaireMapper->insert($questionnaire);
			return $this->redirect( 'salesquestionnaire.questionnaire.show', array( "Id" => $questionnaire->getId(), "response" => array("status"=>"success", "message"=>"New sales questionnaire successfully added") ) );
		} catch (Exception $exception) {
			$this->params['questionnaire'] = $questionnaire;
			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('new', $this->params, $this->renderas);
		}
    }


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function show(){
		try {
			$questionnaire = $this->questionnaireMapper->findByIdAndUser( $this->request->Id, $this->api->getUserId() );
			$this->params['response'] = $this->request->response;
			$this->params['questionnaire'] = $questionnaire;
	        return $this->render('show', $this->params, $this->renderas, array('X-PJAX-URL'=>$this->api->linkToRoute('salesquestionnaire.questionnaire.show', array('Id'=>$this->request->Id)) ));
		} catch (Exception $exception) {
			if ($exception->getMessage() == "No matching entry found") {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_READ ) ) 
						throw new Exception("You don't have permissions to see this sales questionnaire");
					$questionnaire = (array)$this->questionnaireMapper->findById( $shared['item_target'] );
					if ($shared['permissions'] & \OCP\PERMISSION_CREATE) $questionnaire['permissions'][] = "CREATE";
					if ($shared['permissions'] & \OCP\PERMISSION_READ)   $questionnaire['permissions'][] = "READ";
					if ($shared['permissions'] & \OCP\PERMISSION_UPDATE) $questionnaire['permissions'][] = "UPDATE";
					if ($shared['permissions'] & \OCP\PERMISSION_DELETE) $questionnaire['permissions'][] = "DELETE";
					if ($shared['permissions'] & \OCP\PERMISSION_SHARE)  $questionnaire['permissions'][] = "SHARE";
					$this->params['response'] = $this->request->response;
					$this->params['questionnaire'] = $questionnaire;
			        return $this->render('show', $this->params, $this->renderas, array('X-PJAX-URL'=>$this->api->linkToRoute('salesquestionnaire.questionnaire.show', array('Id'=>$this->request->Id)) ));
				} catch (Exception $exception) {
					$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $this->params, $this->renderas);
				}
			}
			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('error', $this->params, $this->renderas);
		}
	}


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function edit(){
		try {
			$this->params['questionnaire'] = $this->questionnaireMapper->findByIdAndUser($this->request->Id, $this->api->getUserId() );
	        return $this->render('edit', $this->params, $this->renderas);
		} catch (Exception $exception) {
			if ($exception->getMessage() == 'No matching entry found' ){
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_UPDATE) ) 
						throw new Exception("You don't have permissions to edit this sales questionnaire");
					$this->params['questionnaire'] = $this->questionnaireMapper->findById($shared['item_target']);
			        return $this->render('edit', $this->params, $this->renderas);
				} catch (Exception $exception) {
					$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $this->params, $this->renderas);					
				}
			}
			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('error', $this->params, $this->renderas);
		}
    }


    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function update(){
		try {
			$questionnaire = $this->questionnaireMapper->findByIdAndUser( $this->request->Id, $this->api->getUserId() );
			//$questionnaire = new Questionnaire;
			//$questionnaire = $questionnaire->fromParams((array)$this->request->parameters);
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
					if ($exception->getMessage() == "You don't have permissions to edit this sales questionnaire") {
						$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
						return $this->render('error', $this->params, $this->renderas);					
					}
					$questionnaire = self::questionnaireFromRequest($this->request);
					$questionnaire->setId($this->request->Id);
					$this->params['questionnaire'] = $questionnaire;
					$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('edit', $this->params, $this->renderas);					
				}
			}
			$questionnaire = $this->questionnaireMapper->findByIdAndUser( $this->request->Id, $this->api->getUserId() );
			$this->params['questionnaire'] = $questionnaire;
 			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('edit', $this->params, $this->renderas);
		}
    }


    /**
     * @CSRFExemption
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function delete(){
		try {
			$questionnaire = $this->questionnaireMapper->findByIdAndUser($this->request->Id, $this->api->getUserId() );
			$this->params['questionnaire'] = $questionnaire;
	        return $this->render('delete', $this->params, $this->renderas);
		} catch (Exception $exception) {
			if ($exception->getMessage = "No matching entry found") {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_DELETE) ) 
						throw new Exception("You don't have permissions to delete this sales questionnaire");
					$questionnaire = (array)$this->questionnaireMapper->findById( $shared['item_target'] );
					$this->params['questionnaire'] = $questionnaire;
			        return $this->render('delete', $this->params, $this->renderas);				
				} catch (Exception $exception) {
					$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $this->params, $this->renderas);				
				}
			}
			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('delete', $this->params, $this->renderas);
		}
	}


    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     */
    public function destroy(){
		try {
			//$questionnaire = new Questionnaire();
			//$questionnaire->setId($this->request->Id);
			$questionnaire = $this->questionnaireMapper->findByIdAndUser($this->request->Id, $this->api->getUserId() );
			$this->questionnaireMapper->delete($questionnaire);
			return $this->redirect(
				"salesquestionnaire.questionnaire.index",
				array("response" => array( "status"=>"success", "message"=>"Sales questionnaire successfully deleted" ) )
			);
		} catch (Exception $exception) {
			if ($exception->getMessage() == "No matching entry found") {
				try {
					$shared = \OCP\Share::getItemSharedWith('salesquestionnaire', $this->request->Id );
					if ( !($shared['permissions'] & \OCP\PERMISSION_DELETE) ) 
						throw new Exception("You don't have permissions to delete this sales questionnaire");
					$questionnaire = $this->questionnaireMapper->findById( $shared['item_target'] );
					$this->questionnaireMapper->delete($questionnaire);
					return $this->redirect( 'salesquestionnaire.questionnaire.index', array("response" => array( "status"=>"success", "message"=>"Sales questionnaire successfully deleted" ) ));
				} catch (Exception $exception) {
					$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
					return $this->render('error', $this->params, $this->renderas);				
				}
			}
			$this->params['response'] = array("status"=>"alert", "message"=>$exception->getMessage(), "code"=>$exception->getCode() );
			return $this->render('delete', $this->params, $this->renderas);
		}
    }
}