<?php
	require_once('../qcubed.inc.php');

	// Create an installation status message.
	/*$arrInstallationMessages = QInstallationValidator::Validate();
	$strConfigStatus = ($arrInstallationMessages) ?
		'<span class="warning">' . count($arrInstallationMessages).' problem(s) found. <a href="' . QCUBED_APP_TOOLS_URL . '/config_checker.php">Click here</a> to view details.</span>' :
		'<span class="success">all OK.</span>';
	*/
	$strPageTitle = 'QCubed Development Framework - Start Page';
	require(QCUBED_PROJECT_CONFIGURATION_DIR . '/header.inc.php');
?>
	<h1 class="page-title">Welcome to QCubed!</h1>
	<div class="install-status">
		<p><strong>If you are seeing this, the framework has been successfully installed.</strong></p>
        <p><?php //		Current installation status:  <?php _p($strConfigStatus, false) ?></p>
	</div>
	<h2>Next Steps</h2>
	<ul class="link-list">
		<li><a href="<?= QCUBED_CODEGEN_URL ?>">Code Generator</a> - to create ORM model objects that map to tables in your database, and ModelConnectors
			and form drafts to edit and display the data.</li>
		<li><a href="form_drafts.php">View Form Drafts</a> - to view the generated files (after you run the Code Generator).</li>
		<li><a href="<?php _p(QCUBED_EXAMPLES_URL) ?>/index.php">QCubed Examples</a> - learn QCubed by studying and modifying the example files locally.</li>
		<li><a href="../test/localtest.php">QCubed Unit Tests</a> - set of tests that QCubed developers use to verify the integrity of the framework.
			You must install the test SQL database and codegen_options.json file to run the tests. These can be found in the <?php _p(QCUBED_EXAMPLES_DIR)?> directory.</li>
	</ul>
<?php if (\QCubed\Project\Application::isAuthorized()) { ?>
	<pre><code><?php \QCubed\Project\Application::varDump(); ?></code></pre>
<?php } ?>
<?php require(QCUBED_PROJECT_CONFIGURATION_DIR . '/footer.inc.php'); ?>