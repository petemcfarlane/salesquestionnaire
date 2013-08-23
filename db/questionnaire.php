<?php
namespace OCA\SalesQuestionnaire\Db;

use \OCA\AppFramework\Db\Entity;

class Questionnaire extends Entity {

	public $createdAt;
	public $uid;
	public $updatedAt;
	public $modifiedBy;
	public $customer;
	public $customerAddress;
	public $customerWebsite;
	public $projectName;
	public $projectType;
	public $platform;
	public $meetingWith;
	public $meetingDate;
	public $meetingLocation;
	public $representative;
	public $meetingPurpose;
	public $technicalAuthority;
	public $commercialAuthority;
	public $technicalRequirements;
	public $commercialRequirements;
	public $otherRequirements;
	public $meetingNotes;
	public $purchaseDecision;
	public $supplyEvaluation;
	public $optimizeBy;
	public $manufactureDate;
	public $retailDate;
	public $minimumOrder;
	public $rampYear1;
	public $rampYear2;
	public $rampYear3;
	public $territories;
	public $retailers;
	public $bom;
	public $rrp;
	public $licenseFee;
	public $budgeted;
	public $oem;
	public $convince;
	public $tasks;
	public $generalNotes;
	public $riskAssessment;

	public function __construct($fromRow=null){
		if ($fromRow) {
			$this->fromRow($fromRow);
			$this->setMeetingWith( self::getContacts($this->getMeetingWith() ) );
			$this->setTechnicalAuthority( self::getContact($this->getTechnicalAuthority() ) );
			$this->setCommercialAuthority( self::getContact($this->getCommercialAuthority() ) );
		}
	}
	
	public function getContact($contactId) {
		if ( empty($contactId) ) return false;
		$query = \OCP\DB::prepare("SELECT `id`, `fullname` FROM `*PREFIX*contacts_cards` WHERE `id` = ?");
		$result = $query->execute( array($contactId) );
		$contact = $result->fetchRow();
		return array("id"=>$contact['id'], "fullname"=>$contact['fullname']);
	}

	public function getContacts($contacts) {
		if ( empty($contacts) ) return false;
		$query = \OCP\DB::prepare("SELECT `id`, `fullname` FROM `*PREFIX*contacts_cards` WHERE `id` IN ($contacts)");
		$result = $query->execute();
		$contacts = $result->fetchAll();
		foreach($contacts as $contact) {
			$return[$contact['id']] = array("id"=>$contact['id'], "fullname"=>$contact['fullname']);
		}
		return $return;
	}
}
