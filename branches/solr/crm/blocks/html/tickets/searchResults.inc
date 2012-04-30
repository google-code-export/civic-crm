<?php
/**
 * Displays a list of Tickets from a Solr response
 *
 * @copyright 2011-2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->tickets
 */
if (count($this->tickets)) {
	echo "
	<div class=\"searchResults\">
	<table>
	";
	$header = new Block(
		'tickets/partials/searchResultsColumnHeaders.inc',
		array('tickets'=>$this->tickets)
	);
	echo $header->render('html',$this->template);
	echo "</table>";

	include APPLICATION_HOME.'/blocks/html/tickets/partials/searchResultsRows.inc';
	echo "
	</div>
	";
}
