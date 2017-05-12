<?php
	$strPageTitle = 'Basic Test';
 	require('../../../../../../project/includes/configuration/header.inc.php');

?>

<?php $this->renderBegin(); ?>
<?php $this->dtg->Render(); ?>
<?php $this->lblVars->Render(); ?>
<?php $this->renderEnd(); ?>
<?php require('../../../../../../project/includes/configuration/footer.inc.php'); ?>