<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<?php
	$this->layout = 'full-width';
	$dir = dirname(__FILE__);
	include $dir.'/partials/header.inc';
?>
<body>
	<?php
		if (!isset($_REQUEST['popup'])) {
			include $dir.'/partials/banner.inc';
			include $dir.'/partials/menubar.inc';
		}
	?>

	<div id="panel-container">
		<div id="content-panel">
			<?php include FRAMEWORK.'/errorMessages.php'; ?>

			<div id="ticket-panel">
				<?php echo $this->includeBlocks('ticket-panel'); ?>
			</div>
			<div id="action-panel">
				<?php
					if (!isset($_REQUEST['popup'])) {
						echo $this->includeBlocks('history-panel');
					}
				?>
			</div>
			<div id="issue-panel">
				<?php
					if (!isset($_REQUEST['popup'])) {
						echo $this->includeBlocks('issue-panel');
					}
				?>
			</div>
			<div id="location-panel">
				<?php
					if (!isset($_REQUEST['popup'])) {
						echo $this->includeBlocks('location-panel');
					}
				?>
			</div>
		</div>
	</div>
	<?php
		include $dir.'/partials/footer.inc';
	?>
</body>
</html>
<?php
	include $dir.'/partials/garbageCollection.inc';
?>