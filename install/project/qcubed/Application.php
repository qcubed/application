<?php

namespace QCubed\Project;

use QCubed;
use QCubed\I18n\TranslationService;

/**
 * Class Application
 *
 * This is the subclass of the main application singleton object. Use this to customize the behavior of the default
 * application, and to add your own globally accessible methods and properties specific to your application.
 *
 * @package QCubed\Project
 * @was QApplication
 */
class Application extends QCubed\ApplicationBase
{
    private static $instance = null;

    // define any services you will need for your application here
    //protected $authService;

    /**
     * @return Application
     */
    public static function instance()
    {
        if (!Application::$instance) {
            Application::$instance = new Application();
        }
        return Application::$instance;
    }

    /**
     * This is called by the PHP5 Autoloader.  This method overrides the
     * one in ApplicationBase.
     *
     * @param string $strClassName
     * @return bool
     */
    public static function autoload($strClassName)
    {
        if (!parent::autoload($strClassName)) {
            // Run any custom autoloading functionality (if any) here...
            // return true; if you find the class
        }
        return false;
    }

    /**
     * Set up your application specific services here.
     */
    public function initializeServices()
    {
        $this->startSession();  // make sure we start the session first in case other services need it.
        $this->initTranslator();

        //$this->authService = new \Project\Service\Auth();
    }

    /**
     * If you want to use a custom session handler, set it up here. The commented example below uses a QCubed handler that
     * puts sessions in a database.
     */
    protected function startSession()
    {
        /*
        QDbBackedSessionHandler::initialize(DB_BACKED_SESSION_HANDLER_DB_INDEX,
            DB_BACKED_SESSION_HANDLER_TABLE_NAME);*/

        // start the session
        session_start();
    }

    /**
     * Initialize the translator singleton. See the I18N library for details on how to configure this.
     * If you do nothing, no translation will happen.
     */
    protected function initTranslator ()
    {
        //$translator = new \QCubed\I18n\SimpleCacheTranslator();
        /*
        $translator->bindDomain('app', __PROJECT__ . "/includes/i18n")  // set to application's i18n directory
            ->setDefaultDomain('app')
            ->setTempDir(TMP);
        TranslationService::instance()->setTranslator($translator);
        */

        // If the user or you want a language other than english, set that here.
        //TranslationService::instance()->setLanguage('es');
    }
}
