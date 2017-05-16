<?php

// Config file for travis build

if (!defined('SERVER_INSTANCE')) {
	// The Server Instance constant is used to help ease web applications with multiple environments.
	// Feel free to use, change or ignore.
	define('SERVER_INSTANCE', 'dev');
	define('ALLOW_REMOTE_ADMIN', true);

	// In the travis test build, the vendor directory is installed inside the application directory
    define ('QCUBED_BASE_DIR',  dirname(dirname(__DIR__)) . '/vendor/qcubed');

    // for travis build only, we point to the project directory inside the install directory
	define ('QCUBED_PROJECT_DIR', dirname(dirname(__DIR__)) . '/install/project');
    define ('QCUBED_PROJECT_GEN_DIR', QCUBED_PROJECT_DIR . '/generated');
    define ('QCUBED_PROJECT_INCLUDES_DIR', QCUBED_PROJECT_DIR . '/includes');

    define ('QCUBED_PROJECT_MODEL_DIR', QCUBED_PROJECT_INCLUDES_DIR . '/model' );
    define ('QCUBED_PROJECT_MODEL_GEN_DIR', QCUBED_PROJECT_GEN_DIR . '/model_base' );

    define ('QCUBED_APP_INCLUDES_DIR', QCUBED_PROJECT_DIR . '/includes/app_includes' );
    define ('QCUBED_ORM_TOOLS_DIR', QCUBED_BASE_DIR . '/orm/tools' );

	require_once (__DIR__ . '/' . getenv("DB") . '.inc.php');

	define ('MAX_DB_CONNECTION_INDEX', 1);

	/** The value for QApplication::$EncodingType constant */
	define('QCUBED_ENCODING', 'UTF-8');

    define ('QCUBED_FORMS_DIR', QCUBED_PROJECT_DIR . '/forms');

    define ('QCUBED_PROJECT_MODELCONNECTOR_DIR', QCUBED_PROJECT_INCLUDES_DIR . '/connector' );
    define ('QCUBED_PROJECT_MODELCONNECTOR_GEN_DIR', QCUBED_PROJECT_GEN_DIR . '/connector_base' );
    define ('QCUBED_PROJECT_DIALOG_DIR', QCUBED_PROJECT_INCLUDES_DIR . '/dialog' );
    define ('QCUBED_PROJECT_DIALOG_GEN_DIR', QCUBED_PROJECT_GEN_DIR . '/dialog_base' );
    define ('QCUBED_PROJECT_PANEL_DIR', QCUBED_PROJECT_INCLUDES_DIR . '/panel' );
    define ('QCUBED_PROJECT_PANEL_GEN_DIR', QCUBED_PROJECT_GEN_DIR . '/panel_base' );

    define('__FORM_STATE_HANDLER__', '\\QCubed\\FormState\\SessionHandler');

    define ('QCUBED_JQUERY_JS', ' http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
    define ('QCUBED_JQUI_JS', ' http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
// The core qcubed javascript file to be used.
// In production or as a performance tweak, you may want to use the compressed "_qc_packed.js" library
    define ('QCUBED_JS',  QCUBED_JS_URL . '/qcubed.js');
//define ('QCUBED_JS',  '_qc_packed.js');

    define ('QCUBED_JQUI_CSS', QCUBED_CSS_URL . '/jquery-ui-themes/ui-qcubed/jquery-ui.custom.css');


}
