<?php require(__CONFIGURATION__ . '/header.inc.php'); ?>
<?php $this->renderBegin(); ?>
<?php $this->list1->RenderWithName(); ?>
<?php $this->btnServer->Render(); ?>
<?php $this->btnAjax->Render(); ?>
<?php $this->renderEnd(); ?>
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>