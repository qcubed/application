<?php require(__PROJECT__ . '/includes/configuration/header.inc.php'); ?>
<?php $this->renderBegin(); ?>
<div>
<?php $this->dtg->Render(); ?>
<?php $this->txtCount->RenderWithName(); ?>
<?php $this->txtPageSize->RenderWithName(); ?>
</div>
<?php $this->renderEnd(); ?>
<?php require(__PROJECT__ . '/includes/configuration/footer.inc.php'); ?>