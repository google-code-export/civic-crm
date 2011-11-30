<?php
/**
 * Find and choose people
 *
 * The user can come here from somewhere they need a person
 * Choosing a person should send them back where they came from,
 * with the chosen person appended to the url
 *
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET return_url
 */
if (!userIsAllowed('People')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL);
	exit();
}
// Look for anything that the user searched for
$search = array();
$fields = array('firstname','lastname','email','organization','department');
foreach ($fields as $field) {
	if (isset($_GET[$field]) && $_GET[$field]) {
		$value = trim($_GET[$field]);
		if ($value) {
			$search[$field] = $value;
		}
	}
}

// Display the search form and any results
$template = !empty($_GET['format']) ? new Template('default',$_GET['format']) : new Template();
if ($template->outputFormat == 'html') {
	$searchForm = new Block('people/searchForm.inc');
	if (isset($_GET['return_url'])) {
		$searchForm->return_url = $_GET['return_url'];
	}
	$template->blocks[] = $searchForm;
}

if (count($search)) {
	if (isset($_GET['setOfPeople'])) {
		switch ($_GET['setOfPeople']) {
			case 'staff':
				$search['username'] = array('$exists'=>true);
				break;
			case 'public':
				$search['username'] = array('$exists'=>false);
				break;
		}
	}
	$personList = new PersonList();
	$personList->search($search);
	$searchResults = new Block('people/searchResults.inc',array('personList'=>$personList));
	if (isset($_GET['return_url'])) {
		$searchResults->return_url = $_GET['return_url'];
	}
	$template->blocks[] = $searchResults;
}
echo $template->render();
