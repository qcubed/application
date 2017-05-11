<?php

define ('__CONFIGURATION__', __INCLUDES__ . '/configuration');

// The application includes directory
define ('__APP_INCLUDES__', __INCLUDES__ . '/app_includes');

/* Absolute File Paths for Internal Directories
 *
 * Please specify the absolute file path for all the following directories in your QCubed-based web
 * application.
 *
 * Note that all paths must start with a slash or 'x:\' (for windows users) and must have
 * no ending slashes.  (We take advantage of the __INCLUDES__ to help simplify this section.
 * But note that this is NOT required.  These directories can also reside outside of the
 * Document Root altogether.  So feel free to use or not use the __DOCROOT__ and __INCLUDES__
 * constants as you wish/need in defining your other directory constants.)
 */

// The QCubed Directories

define ('__TMP__', __PROJECT__  . '/tmp');
define ('__CACHE__', __TMP__ . '/cache');
define ('__FILE_CACHE__', __TMP__ . '/cache');

define ('__PLUGIN_TMP__', __TMP__ . '/plugin.tmp/');

define ('__PLUGINS__', __DOCROOT__ . __SUBDIRECTORY__ . '/vendor/qcubed/plugin');

// The QCubed Core
//define ('__QCUBED_CORE__', __DOCROOT__ . __SUBDIRECTORY__ . '/vendor/qcubed/qcubed/includes');
//define ('__QCUBED_CORE__', __DOCROOT__ . __SUBDIRECTORY__ . '/vendor/qcubed/qcubed/includes/qcubed/_core');


// If using HTML Purifier, the location of the writeable cache directory.
define ('__PURIFIER_CACHE__', __CACHE__ . '/purifier');
