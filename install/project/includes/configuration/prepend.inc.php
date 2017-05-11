<?php
if (!defined('__PREPEND_INCLUDED__')) {
    // Ensure prepend.inc is only executed once
    define('__PREPEND_INCLUDED__', 1);


    ///////////////////////////////////
    // Define Server-specific constants
    ///////////////////////////////////
    /*
     * This assumes that the configuration include file is in the same directory
     * as this prepend include file.  For security reasons, you can feel free
     * to move the configuration file anywhere you want.  But be sure to provide
     * a relative or absolute path to the file.
     */
    if (file_exists(__DIR__ . '/configuration.inc.php')) {
        require(__DIR__ . '/configuration.inc.php');
    }
    else {
        // The minimal constants set to work
        define ('__DOCROOT__', dirname(__FILE__) . '/../../..');
        define ('__PROJECT__', dirname(__FILE__) . '/../..');
        define ('__INCLUDES__', dirname(__FILE__) . '/..');
        define ('__QCUBED__', __INCLUDES__); // needs to be reconfigured
        define ('__PLUGINS__', __PROJECT__ . '/generated/plugins');
        define ('__QCUBED_CORE__', __DOCROOT__ . '/vendor/qcubed/qcubed/includes');
        define ('__APP_INCLUDES__', __INCLUDES__ . '/app_includes');
        define ('__MODEL__', __INCLUDES__ . '/model' );
        define ('__MODEL_CONNECTOR__', __INCLUDES__ . '/meta_controls');
        define ('__META_CONTROLS_GEN__', __PROJECT__ . '/generated/meta_base');
        define ('__MODEL_GEN__', __PROJECT__ . '/generated/model_base' );
    }

    require_once(QCUBED_BASE_DIR . '/application/src/version.inc.php');     // Include the hard-coded QCubed version number
    require_once(QCUBED_BASE_DIR . '/application/src/Error/Manager.php');   // Include the error manager so we can process errors immediately
    \QCubed\Error\Manager::initialize();

    //////////////////////////////
    // Register the autoloader so we can find our files
    //////////////////////////////
    require_once(QCUBED_BASE_DIR . '/common/src/AutoloaderService.php');   // Find the autoloader
    \QCubed\AutoloaderService::instance()
        ->initialize(dirname(QCUBED_BASE_DIR) )   // register with the vendor directory
        ->addPsr4('QCubed\\Project\\', __PROJECT__ . '/qcubed')
        ->addPsr4('QCubed\\Plugin\\', __PROJECT__ . '/includes/plugins')
        ->addClassmapFile(__APP_INCLUDES__ . '/app_includes.inc.php')
        // temp includes
        ->addPsr4('QCubed\\', QCUBED_BASE_DIR . '/application/src')
        ->addPsr4('QCubed\\', QCUBED_BASE_DIR . '/common/src')
        ->addPsr4('QCubed\\', QCUBED_BASE_DIR . '/orm/src')
        ->addPsr4('QCubed\\I18n\\', QCUBED_BASE_DIR . '/i18n/src')
        ->addPsr4('QCubed\\Cache\\', QCUBED_BASE_DIR . '/cache/src')
        ->addPsr4('QCubed\\Codegen\\Generator\\', QCUBED_BASE_DIR . '/application/codegen/generator')
    ;

    if (file_exists(__MODEL_GEN__ . '/_class_paths.inc.php')) {
        \QCubed\AutoloaderService::instance()->addClassmapFile(__MODEL_GEN__ . '/_class_paths.inc.php');
    }
    if (file_exists(__MODEL_GEN__ . '/_type_class_paths.inc.php')) {
        \QCubed\AutoloaderService::instance()->addClassmapFile(__MODEL_GEN__ . '/_type_class_paths.inc.php');
    }

    // Register the custom autoloader, making sure we go after the previous autoloader
    spl_autoload_register(array('\\QCubed\\Project\\Application', 'autoload'), true, false);

    /*
    if (defined('__APP_INCLUDES__')) {
        require_once(__APP_INCLUDES__ . '/app_includes.inc.php');    // autoload local files
    }
*/
    //////////////////////////
    // Custom Global Functions
    //////////////////////////
    // Define any custom global functions (if any) here...


    ////////////////
    // Include Files
    ////////////////
    // Include any other include files (if any) here...

    require_once(QCUBED_BASE_DIR . '/i18n/tools/i18n-app.inc.php'); // Include the translation shortcuts. See the Application for translation setup.
    require_once(QCUBED_BASE_DIR . '/application/src/utilities.inc.php');     // Shortcuts used throughout the framework
    require_once(QCUBED_BASE_DIR . '/orm/src/model_includes.inc.php');     // Shortcuts used throughout the framework

    ////////////////////////////////////////////////
    // Initialize the Application and DB Connections
    ////////////////////////////////////////////////
    \QCubed\Database\Service::initializeDatabaseConnections();
    \QCubed\Project\Application::instance()->initializeServices();

    \QCubed\Project\Application::startOutputBuffering();
}