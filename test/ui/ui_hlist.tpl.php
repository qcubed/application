<?php require(QCUBED_CONFIG_DIR . '/header.inc.php'); ?>
<?php $this->renderBegin(); ?>
<?php $this->list1->RenderWithName(); ?>
<?php $this->btnServer->Render(); ?>
<?php $this->btnAjax->Render(); ?>
<?php $this->renderEnd(); ?>
<?php require(QCUBED_CONFIG_DIR . '/footer.inc.php'); ?>