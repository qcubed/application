<?php
	require_once('qcubed.inc.php');
	require('includes/examples.inc.php');

	$strCategoryId = \QCubed\Project\Application::instance()->context()->pathItem(0);
	$strExampleId = \QCubed\Project\Application::instance()->context()->pathItem(1);
	if ($strCategoryId == 'plugin') {
		$strSubId = \QCubed\Project\Application::instance()->context()->pathItem(2);
		$strScript = \QCubed\Project\Application::instance()->context()->pathItem(3);
	} else {
		$strSubId = null;
		$strScript = \QCubed\Project\Application::instance()->context()->pathItem(2);
	}

	$strReference = Examples::GetExampleScriptPath($strCategoryId, $strExampleId);
	$strName = Examples::GetExampleName($strCategoryId, $strExampleId);

	if (!$strScript) {
		$strUrl = \QCubed\Project\Application::instance()->context()->requestUri() . substr($strReference, strrpos($strReference, '/'));
		\QCubed\Project\Application::Redirect($strUrl, true);
	}
?>
<html>
	<head>
		<meta charset="<?php _p(\QCubed\Project\Application::encodingType()); ?>" />
		<title>QCubed PHP 5 Development Framework - View Source</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(QCUBED_CSS_URL . '/styles.css'); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php _p(QCUBED_EXAMPLES_URL . '/includes/examples.css'); ?>" />
	</head>
	<body>
		<div id="closeWindow"><a href="javascript:window.close()" class="close-window">Close this Window</a></div>
		<header><nav class="page-links"><span class="headerSmall"><?php _p(Examples::CodeLinks($strCategoryId, $strExampleId, $strSubId, $strScript), false); ?></nav></header>
		<section id="content">
<?php
	// Filename Cleanup
	if (($strScript == 'header.inc.php') || ($strScript == 'footer.inc.php') || ($strScript == 'examples.css'))
		$strFilename = 'includes/' . $strScript;
	else if (($strScript == 'mysql_innodb.sql') || ($strScript == 'sql_server.sql')) {
		$strFilename = $strScript;
	} else if (substr($strScript, 0, 16) == '__CORE_CONTROL__') {
		$strFilename = __QCUBED__ . '/controls/' . str_replace('__CORE_CONTROL__', '', str_replace('/', '', $strScript));
	} else if (substr($strScript, 0, 18) == '__CORE_FRAMEWORK__') {
		$strFilename = __QCUBED_CORE__ . '/framework/' . str_replace('__CORE_FRAMEWORK__', '', str_replace('/', '', $strScript));
	} else {		
		$strFilename = substr($strReference, 1);
		// todo: fix this
		$strFilename = __DOCROOT__ . '/' . substr($strFilename, strlen(__VIRTUAL_DIRECTORY__), strrpos($strReference, '/') - strlen(__VIRTUAL_DIRECTORY__)) . $strScript;
	}

	if (!file_exists($strFilename)) {
		throw new Exception("Example file does not exist: " . $strFilename);
	}
?>
			<h1>Source of: <?php _p(preg_replace('/__.*__/', '', $strScript)); ?></h1>
			<pre><?php highlight_file($strFilename); ?></pre>
		</section>
	</body>
</html>