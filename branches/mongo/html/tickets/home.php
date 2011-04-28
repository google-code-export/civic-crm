<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Tickets')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL);
	exit();
}

$template = new Template('search');
$template->blocks['search-form'][] = new Block('tickets/searchForm.inc');
// Map the form fields to the Ticket search fields
$fields = array(
	'enteredByPerson'=>'enteredByPerson._id',
	'assignedPerson'=>'assignedPerson._id',
	'department'=>'department._id',
	'city'=>'city',
	'state'=>'state',
	'zip'=>'zip',
	'type'=>'issues.type',
	'category'=>'issues.category._id',
	'contactMethod'=>'issues.contactMethod',
	'status'=>'status',
	'action'=>'history.action',
	'actionPerson'=>'history.actionPerson._id'
);
if (count(array_intersect(array_keys($fields),array_keys($_GET)))) {
	$search = array();
	foreach ($fields as $field=>$key) {
		if (isset($_GET[$field])) {
			$value = trim($_GET[$field]);
			if ($value) {
				$search[$key] = $value;
			}
		}
	}
	
	if (count($search)) {
		$ticketList = new TicketList($search);

		$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
		$paginator = $ticketList->getPaginator(50,$page);
		
		$template->blocks['search-results'][] = new Block(
			'tickets/searchResults.inc',
			array(
				'ticketList'=>$paginator,
				'title'=>'Search Results',
				'fields'=>isset($_GET['fields']) ? $_GET['fields'] : null
			)
		);
		$template->blocks['search-results'][] = new Block(
			'pageNavigation.inc',array('paginator'=>$paginator)
		);

	}
}

echo $template->render();
