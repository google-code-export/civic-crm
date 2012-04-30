<?php
/**
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../../configuration.inc';
$mongo = Database::getConnection();
$search = new Search();

$results = $mongo->tickets->find();
$c = 0;
foreach ($results as $row) {
	$ticket = new Ticket($row);
	$search->add($ticket);
	$c++;
	echo "$c: {$ticket->getId()}\n";
}
echo "Committing\n";
$solr->solrClient->commit();
echo "Optimizing\n";
$solr->solrClient->optimize();
echo "Done\n";