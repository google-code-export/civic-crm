<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param $_GET location
 */
// Make sure we have the location in the system
$ticketList = new TicketList(array('location'=>$_GET['location']));
if (!count($ticketList)) {
	$url = new URL(BASE_URL.'locations');
	$url->location_query = $_GET['location'];
	header("Location: $url");
	exit();
}

$template = new Template('locations');
$template->blocks['location-panel'][] = new Block(
	'locations/locationInfo.inc',array('location'=>$_GET['location'])
);
$template->blocks['ticket-panel'][] = new Block(
	'tickets/searchResults.inc',
	array(
		'ticketList'=>new TicketList(array('location'=>$_GET['location'])),
		'title'=>'Tickets Associated with this Location',
		'fields'=>array(
			'ticket-id'=>1,
			'ticket-enteredDate'=>1,
			'ticket-assignedPerson'=>1,
			'ticket-status'=>1,
			'issue-issueType'=>1,
			'issue-reportedByPerson'=>1
		)
	)
);

echo $template->render();