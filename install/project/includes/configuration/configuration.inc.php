<?php
/**
 * configuration.inc.php
 *
 * This configuration file simply loads the configuration files in the active directory.
 * See that directory for more info.
 * config.cfg.php is loaded first
 */

if (!defined('QCUBED_CONFIG__INCLUDED')) {
	define ("QCUBED_CONFIG__INCLUDED", 1);	// notify other scripts that the config file is loaded, and prevent multiple loads

	$dirpath = dirname(__FILE__);
	$dirpath = realpath($dirpath . '/active');
	if ($dirpath !== false) {	// does the active directory exist?
		if (file_exists($dirpath . '/config.cfg.php')) {
			require ($dirpath . '/config.cfg.php');
		}
		foreach (new DirectoryIterator($dirpath) as $fileInfo) {
			if($fileInfo->isDot()) continue;
			if ($fileInfo->getFilename() === 'config.cfg.php') continue;
			$strFileName = $fileInfo->getPathname();
			if (substr($strFileName, -8) == '.cfg.php') {
				require ($strFileName);
			}
		}
	}
}
