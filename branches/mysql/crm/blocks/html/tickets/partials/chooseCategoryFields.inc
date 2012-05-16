<?php
/**
 * @copyright 2011-2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param string $this->return_url
 */
?>
<label>
	Choose a category
	<select name="category_id">
	<?php
		if ($_SESSION['USER']->getDepartment()) {
			if (isset($_REQUEST['category_id'])) {
				try {
					$category = new Category($_REQUEST['category_id']);
					$name = View::escape($category->getName());
					echo "
					<option value=\"{$category->getId()}\" selected=\"selected\">$name</option>
					";
				}
				catch (Exception $e) {
					# Just ignore bad categories.  The user will just have to pick another
				}
			}

			$dept = $_SESSION['USER']->getDepartment();
			$department = new Department($dept['_id']);

			foreach ($department->getCategories() as $departmentCategory) {
				$name = View::escape($departmentCategory['name']);
				echo "<option value=\"$departmentCategory[_id]\">$name</option>";
			}
		}
	?>
	</select>
</label>
<div>
	<?php
		$url = new URL(BASE_URL.'/categories/choose');
		// Preserve any extra parameters passed in
		foreach ($_REQUEST as $key=>$value) {
			if ($key!='ticket_id' && $key!='return_url') {
				$url->$key = $value;
			}
		}
		$url->return_url = $this->return_url;
		echo "<a href=\"$url\">Choose from all categories</a>";
	?>
</div>