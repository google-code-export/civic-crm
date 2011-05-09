<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param REQUEST issue_id
 */
// Make sure they're supposed to be here
if (!userIsAllowed('Issues')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL);
	exit();
}

// Load the issue
try {
	$issue = new Issue($_REQUEST['issue_id']);
	if (isset($_REQUEST['person_id'])) {
		$issue->setReportedByPerson_id($_REQUEST['person_id']);
	}
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	header('Location: '.BASE_URL);
	exit();
}

// Handle what the user posts
if (isset($_POST['contactMethod_id'])) {
	$history = new IssueHistory();
	$history->setIssue($issue);
	$history->setAction('response');
	$history->setEnteredByPerson_id($_SESSION['USER']->getPerson_id());
	$history->setActionPerson_id($issue->getReportedByPerson_id());

	$history->setContactMethod_id($_POST['contactMethod_id']);
	$history->setNotes($_POST['notes']);

	try {
		$history->save();
		header('Location: '.$issue->getCase()->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

// Display the view
$template = new Template('cases');
$template->blocks['case-panel'][] = new Block(
	'cases/caseInfo.inc',
	array('case'=>$issue->getCase())
);
$template->blocks['history-panel'][] = new Block(
	'cases/history.inc',
	array('caseHistory'=>$issue->getCase()->getHistory())
);
$template->blocks['issue-panel'][] = new Block(
	'cases/responseForm.inc',
	array('issue'=>$issue)
);
$template->blocks['location-panel'][] = new Block(
	'locations/locationInfo.inc',
	array('location'=>$issue->getCase()->getLocation())
);
$template->blocks['location-panel'][] = new Block(
	'cases/caseList.inc',
	array(
		'caseList'=>new CaseList(array('location'=>$issue->getCase()->getLocation())),
		'title'=>'Other cases for this location',
		'disableButtons'=>true,
		'filterCase'=>$issue->getCase()
	)
);
echo $template->render();
