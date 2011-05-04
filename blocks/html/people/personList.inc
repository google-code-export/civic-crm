<?php
/**
 * @copyright 2009-2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PersonList $this->personList
 * @param string $this->return_url (optional) The url to use for each of th search results
 */
?>
<div class="interfaceBox">
	<h1>
		People
	</h1>
	<table>
		<thead>
			<tr><th>ID</th>
				<th>Username</th>
				<th>Name</th>
				<th>Email</th>
				<th>Organization</th>
			</tr>
		</thead>
		<tbody>
		<?php
			if ($this->return_url) {
				$return_url = new URL($this->return_url);
			}
			foreach ($this->personList as $person) {
				if (isset($return_url)) {
					$return_url->person_id = "{$person->getId()}";
					$url = "$return_url";
				}
				else {
					$url = $person->getURL();
				}

				$name = View::escape($person->getFullname());
				$organization = View::escape($person->getOrganization());
				echo "
				<tr><td>{$person->getId()}</td>
					<td>{$person->getUsername()}</td>
					<td><a href=\"$url\">$name</a></td>
					<td>{$person->getEmail()}</td>
					<td>$organization</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
<?php
	if (userIsAllowed('Users')) {
		echo $this->linkButton(
			'Add a Person',BASE_URL.'/people/updatePerson.php','add',null,null,'button'
		);
	}
?>
</div>