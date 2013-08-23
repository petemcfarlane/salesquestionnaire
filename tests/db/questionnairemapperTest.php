<?php
namespace OCA\SalesQuestionnaire\Db;

use \OCA\AppFramework\Utility\MapperTestUtility;
use \OCA\AppFramework\Core\API;
use \OCA\Salesquestionnaire\Db\Questionnaire;
use \OCA\Salesquestionnaire\Db\Questionnairemapper;

require_once(__DIR__ . "/../classloader.php");

class QuestionnaireMapperTest extends MapperTestUtility {

	private $questionnaireMapper;

	public function setUp() {
		$this->beforeEach();
		$this->questionnaireMapper = new QuestionnaireMapper($this->api);
		$this->user = "Pete";
	}


	public function testMapperShouldSetTableName() {
		$this->assertEquals('*PREFIX*salesquestionnaire', $this->questionnaireMapper->getTableName());
	}

	public function testFindById() {
		$sql = 'SELECT * FROM `*PREFIX*salesquestionnaire` WHERE `id` = ?';
		$params = array('SELECT * FROM `*PREFIX*salesquestionnaire` WHERE `id` = ?');
		$rows = array(
			array('hi')
		);
		$row = $this->setMapperResult($sql, $params, $rows);
		$this->questionnaireMapper->findById($sql, $params);
		//$response = $this->questionnaireMapper->findById($this->user);
		//$this->assertTrue($response instanceof Questionnaire);
	}
}
