<?php

/**
 * The base configuration file for the travis test. This is set as the bootstrap file in the phpunit.xml file.
 */
$workingDir = getcwd();
define('__WORKING_DIR__', $workingDir);

// Configure
require( __WORKING_DIR__ . '/test/travis/prepend.inc.php');
define ('__CONFIGURATION__', __WORKING_DIR__ . '/test/travis');

// Codegen
require(__CONFIGURATION__ . '/CodegenBase.php');
require( QCUBED_BASE_DIR . '/orm/tools/codegen.cli.php');

