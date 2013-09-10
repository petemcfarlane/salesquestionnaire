<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('salesquestionnaire');
OCP\JSON::callCheck();

if (isset($_POST['contactId']) && is_numeric($_POST['contactId'])) {
	try {
		// get contact
		$query  = OCP\DB::prepare('SELECT * FROM *PREFIX*contacts_cards WHERE id = ?');
		$result = $query->execute( array ( $_POST['contactId']) );
		$data   = $result->fetchRow();
		
		// get addressbook ids that current user can read
		$addressbooks =  OCA\Contacts\Addressbook::all(OCP\User::getUser());
		$aids = array();
		foreach ($addressbooks as $addressbook) {
			$aids[] = $addressbook['id'];
		}

		if (!in_array($data['addressbookid'], $aids) ) throw new Exception("Contact not in your addressbook");
		
		$vcard = Sabre\VObject\Reader::read($data['carddata']);
		$contact['details'] = OCA\Contacts\VCard::structureContact($vcard);
		$contact['id'] 		= $data['id'];
		$contact['aid']     = $data['addressbookid'];
	
	 	print json_encode($contact);

	} catch (Exception $exception) {
		print json_encode($exception->getMessage());
	}
}
