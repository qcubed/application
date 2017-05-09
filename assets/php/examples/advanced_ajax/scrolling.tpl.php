<?php require('../includes/header.inc.php'); ?>
<?php $this->renderBegin(); ?>

<div id="instructions">
	<h1>Automatic Scrolling</h1>

	<p>This is just a simple example to show how moveable controls and handles
	will also automatically perform scrolling tasks on the browser window as
	you drag a "move handle" towards the edge of the window.</p>

	<p>There is nothing you need to do to enable this feature.  It will just happen
	automatically.</p>

	<p>Note that other than the large, oversized table we added below, the
	form definition code and the HTML include files are more or less the
	exact same.</p>
</div>
<div id="demoZone">
	<table style="width:<?php _p((ord('J') - ord('A') + 1) * 200); ?>px;">
<?php
		// Create the cells for our oversized table
		for ($intRow = 1; $intRow < 10; $intRow++) {
			_p('<tr>', false);

			for ($intOrd = ord('A'); $intOrd <= ord('J'); $intOrd++) {
				printf('<td class="tc">%s%s</td>', chr($intOrd), $intRow);
			}

			_p('</tr>', false);
		}
?>
	</table>

	<?php $this->pnlPanel->Render('Cursor=move', 'BackColor=#f6f6f6', 'Width=130', 'Height=50', 'Padding=10', 'BorderWidth=1'); ?>
</div>

<?php $this->renderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>