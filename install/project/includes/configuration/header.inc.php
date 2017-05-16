<?php
	// This example header.inc.php is intended to be modfied for your application.

use QCubed as Q;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php echo(QCUBED_ENCODING); ?>" />
<?php if (isset($strPageTitle)) { ?>
		<title><?php Q\QString::htmlEntities($strPageTitle); ?></title>
<?php } ?>
		<link href="<?= QCUBED_CSS_URL ?>/styles.css" rel="stylesheet">
		<?php if (isset($this)) $this->RenderStyles(); ?>
	</head>
	<body>
		<section id="content">