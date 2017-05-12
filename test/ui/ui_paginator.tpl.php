<?php require(__CONFIGURATION__ . '/header.inc.php'); ?>
<?php $this->renderBegin(); ?>
<div>
<?php $this->dtg->Render(); ?>
<?php $this->txtCount->RenderWithName(); ?>
<?php $this->txtPageSize->RenderWithName(); ?>
</div>
<?php $this->renderEnd(); ?>
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>