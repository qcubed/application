<?php require(__PROJECT__ . '/includes/configuration/header.inc.php'); ?>
<?php $this->renderBegin(); ?>
	<div>
		<?php $this->txt1->RenderWithName(); ?>
		<?php $this->lblTxt1Change->RenderWithName(); ?>
		<?php $this->lblTxt1KeyUp->RenderWithName(); ?>
	</div>
	<div>
		<?php $this->chk->RenderWithName(); ?>
		<?php $this->lblCheck->RenderWithName(); ?>
	</div>
<?php $this->renderEnd(); ?>
<?php require(__PROJECT__ . '/includes/configuration/footer.inc.php'); ?>