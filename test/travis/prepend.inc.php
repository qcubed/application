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
    require(__DIR__ . '/configuration.inc.php');

    // this is a somewhat unique setup. The application code we are trying to test is outside this directory, but everything else is in the vendor directory

    define ('__QC__', __DIR__ . '/../../../');

    require_once(__QC__ . '/application/src/version.inc.php');     // Include the hard-coded QCubed version number
    require_once(__QC__ . '/application/src/Error/Manager.php');   // Include the error manager so we can process errors immediately
    \QCubed\Error\Manager::initialize();

    //////////////////////////////
    // Register the autoloader so we can find our files
    //////////////////////////////
    require_once(QCUBED_BASE_DIR . '/common/src/AutoloaderService.php');   // Find the autoloader
    \QCubed\AutoloaderService::instance()
        ->initialize(dirname(QCUBED_BASE_DIR) )   // register with the vendor directory
        ->addPsr4('QCubed\\Project\\', QCUBED_PROJECT_DIR . '/qcubed')
        ->addPsr4('QCubed\\Plugin\\', QCUBED_PROJECT_DIR . '/includes/plugins')
        ->addClassmapFile(QCUBED_APP_INCLUDES_DIR . '/app_includes.inc.php')
        // temp includes
        ->addPsr4('QCubed\\', __QC__ . '/application/src')
        ->addPsr4('QCubed\\', QCUBED_BASE_DIR . '/common/src')
        ->addPsr4('QCubed\\', QCUBED_BASE_DIR . '/orm/src')
        ->addPsr4('QCubed\\I18n\\', QCUBED_BASE_DIR . '/i18n/src')
        ->addPsr4('QCubed\\Cache\\', QCUBED_BASE_DIR . '/cache/src')
        ->addPsr4('QCubed\\Codegen\\Generator\\', __QC__ . '/application/codegen/generator')
    ;

    if (file_exists(QCUBED_PROJECT_MODEL_GEN_DIR . '/_class_paths.inc.php')) {
        \QCubed\AutoloaderService::instance()->addClassmapFile(QCUBED_PROJECT_MODEL_GEN_DIR . '/_class_paths.inc.php');
    }
    if (file_exists(QCUBED_PROJECT_MODEL_GEN_DIR . '/_type_class_paths.inc.php')) {
        \QCubed\AutoloaderService::instance()->addClassmapFile(QCUBED_PROJECT_MODEL_GEN_DIR . '/_type_class_paths.inc.php');
    }

    // Register the custom autoloader, making sure we go after the previous autoloader
    spl_autoload_register(array('\\QCubed\\Project\\Application', 'autoload'), true, false);

    /*
    if (defined('QCUBED_APP_INCLUDES_DIR')) {
        require_once(QCUBED_APP_INCLUDES_DIR . '/app_includes.inc.php');    // autoload local files
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
    require_once(__QC__ . '/application/src/utilities.inc.php');     // Shortcuts used throughout the framework
    //require_once(QCUBED_BASE_DIR . '/orm/src/model_includes.inc.php');     // Shortcuts used throughout the framework
    require_once(QCUBED_PROJECT_MODEL_GEN_DIR . '/QQN.php');     // Shortcuts used throughout the framework

    ////////////////////////////////////////////////
    // Initialize the Application and DB Connections
    ////////////////////////////////////////////////
    \QCubed\Database\Service::initializeDatabaseConnections();
    \QCubed\Project\Application::instance()->initializeServices();

 //   \QCubed\Project\Application::startOutputBuffering();
}