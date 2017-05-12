<?php
	// This example header.inc.php is intended to be modfied for your application.

use QCubed as Q;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php echo(__APPLICATION_ENCODING_TYPE__); ?>" />
<?php if (isset($strPageTitle)) { ?>
		<title><?php Q\QString::htmlEntities($strPageTitle); ?></title>
<?php } ?>
		<link href="<?= __VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ ?>/styles.css" rel="stylesheet">
		<?php if (isset($this)) $this->RenderStyles(); ?>
	</head>
	<body>
		<section id="content">