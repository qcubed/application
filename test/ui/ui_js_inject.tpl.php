<?php require(__CONFIGURATION__ . '/header.inc.php'); ?>
<?php $this->renderBegin(); ?>
<?php $this->panel->Render(); ?>
<?php $this->btnServer->Render(); ?>
<?php $this->btnAjax->Render(); ?>
<?php $this->renderEnd(); ?>
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>