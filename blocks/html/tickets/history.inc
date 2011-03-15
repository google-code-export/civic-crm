<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param TicketHistoryList $this->ticketHistory
 */
?>
<h1>History</h1>
<div class="history">
	<table>
		<tr><th>Date</th><th>Staff<th>Action</th><th>Comments</th></tr>
		<tbody>
		<?php
			foreach ($this->ticketHistory as $event) {
				$action = View::escape($event->getAction()->getName());
				$notes = View::escape($event->getNotes());
				$name = View::escape($event->getEnteredByPerson()->getFullName());
				echo "
				<tr><td>{$event->getEnteredDate()}</td>
					<td>$name</td>	
					<td>$action</td>
					<td>$notes</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>