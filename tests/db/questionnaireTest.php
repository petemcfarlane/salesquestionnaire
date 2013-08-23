<?php

namespace OCA\SalesQuestionnaire\Db;

require_once(__DIR__ . "/../classloader.php");

use \OCA\Salesquestionnaire\Db\Questionnaire;

class QuestionnaireTest extends \PHPUnit_Framework_TestCase {


	private $questionnaire;

	protected function setUp(){
		$this->questionnaire = new Questionnaire();
	}

	public function testResetUpdatedFields(){
		$questionnaire = new Questionnaire();
		$questionnaire->setId(3);
		$questionnaire->resetUpdatedFields();

		$this->assertEquals(array(), $questionnaire->getUpdatedFields());
	}

	public function testFromRow(){
		$row = array('customer' => 'john', 'project_name' => 'john@something.com');
		$this->questionnaire->fromRow($row);
		$this->assertEquals($row['customer'], $this->questionnaire->getCustomer());
		$this->assertEquals($row['project_name'], $this->questionnaire->getProjectName());
	}


	public function testGetSetId(){
		$id = 3;
		$this->questionnaire->setId(3);
		$this->assertEquals($id, $this->questionnaire->getId());
	}


	public function testColumnToPropertyNoReplacement(){
		$column = 'my';
		$this->assertEquals('my', $this->questionnaire->columnToProperty($column));
	}


	public function testColumnToProperty(){
		$column = 'my_attribute';
		$this->assertEquals('myAttribute', $this->questionnaire->columnToProperty($column));
	}


	public function testPropertyToColumnNoReplacement(){
		$property = 'my';
		$this->assertEquals('my', $this->questionnaire->propertyToColumn($property));
	}
}