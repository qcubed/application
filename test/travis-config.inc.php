<?php

/**
 * The base configuration file for the travis test. This is set as the bootstrap file in the phpunit.xml file.
 */
$workingDir = getcwd();
define('__WORKING_DIR__', $workingDir);

// Configure
require( __WORKING_DIR__ . '/test/travis/prepend.inc.php');

define ('QCUBED_PROJECT_CONFIGURATION_DIR', __WORKING_DIR__ . '/test/travis');

require(QCUBED_PROJECT_CONFIGURATION_DIR . '/CodegenBase.php');
require( QCUBED_ORM_TOOLS_DIR . '/codegen.cli.php');

