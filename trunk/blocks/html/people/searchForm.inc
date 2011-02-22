<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$fields = array('name','email','address','phone');
foreach ($fields as $field) {
	$$field = isset($_GET[$field]) ? View::escape($_GET[$field]) : '';
}
?>
<div class="searchForm">
<h1>Find Someone</h1>
<form method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<fieldset>
		<table>
			<tr><td><label for="name">Name</label></td>
				<td><input name="name" id="name" value="<?php echo $name; ?>" /></td>
			</tr>
			<tr><td><label for="email">Email</label></td>
				<td><input name="email" id="email" value="<?php echo $email; ?>" /></td>
			</tr>
			<tr><td><label for="address">Address</label></td>
				<td><input name="address" id="address" value="<?php echo $address; ?>" /></td>
			</tr>
			<tr><td><label for="phone">Phone</label></td>
				<td><input name="phone" id="phone" value="<?php echo $phone; ?>" /></td>
			</tr>
		</table>
		<?php
			echo $this->template->formButton(
				'Search','submit','search'
			);
		?>
	</fieldset>
</form>
</div>