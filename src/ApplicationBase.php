<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed;

use QCubed\Exception\Caller;
use QCubed\Project\Application;
use QCubed\Js;
use QCubed\Database\Service as DatabaseService;

/**
 * This is the base class for the singleton application object. It contains utility code and code to aid the communication
 * with the client. The difference between this and the QForm class is that anything in the application class must be
 * recreated on every entry into to the server, whereas the QForm class uses its built-in serialization mechanism to
 * recreate itself on each entry. This means that any information that should persist for the user as they move
 * through the application should go in the Form, and anything that is just used at the moment to build a response
 * should go here.
 */
class ApplicationBase extends ObjectBase
{
    // These constants help us to organize and build a list of responses to the client.
    const PRIORITY_STANDARD = '*jsMed*';
    const PRIORITY_HIGH = '*jsHigh*';
    const PRIORITY_LOW = '*jsLow*';
    /** Execute ONLY this command and exclude all others */
    const PRIORITY_EXCLUSIVE = '*jsExclusive*';
    /** Execute this command after all ajax commands have been completely flushed */
    const PRIORITY_LAST = '*jsFinal*';

    /**
     * @var bool Set to true to turn on short-term caching. This is an in-memory cache that caches database
     * objects only for as long as a single http request lasts. Depending on your application, this may speed
     * up your database accesses. It DOES increase the amount of memory used in a request.
     * */
    public static $blnLocalCache = false;

    /**
     * Definition of CacheControl for the HTTP header.  In general, it is
     * recommended to keep this as "private".  But this can/should be overriden
     * for file/scripts that have special caching requirements (e.g. dynamically
     * created images like QImageLabel).
     *
     * @var string CacheControl
     */
    public static $CacheControl = 'private';

    /**
     * @var #P#C\QCrossScripting.Purify|?
     * Defines the default mode for controls that need protection against
     * cross-site scripting. Can be overridden at the individual control level,
     * or for all controls by overriding it in QApplication.
     *
     * Set to QCrossScripting::Legacy for backward compatibility reasons for legacy applications;
     * For new applications the recommended setting is QCrossScripting::Purify.
     */
//    public static $DefaultCrossScriptingMode = QCrossScripting::Legacy;

    /**
     * Whether or not we are currently trying to Process the Output of the page.
     * Used by the OutputPage PHP output_buffering handler.  As of PHP 5.2,
     * this gets called whenever ob_get_contents() is called.  Because some
     * classes like QFormBase utilizes ob_get_contents() to perform template
     * evaluation without wanting to actually perform OutputPage, this flag
     * can be set/modified by QFormBase::EvaluateTemplate accordingly to
     * prevent OutputPage from executing.
     *
     * Also set this to false if you are outputting custom headers, especially
     * if you send your own "Content-Type" header.
     *
     * @var boolean ProcessOutput
     */
    public static $ProcessOutput = true;

    /**
     * The content type to output.
     *
     * @var string ContentType
     */
    public static $ContentType = "text/html";


    /** @var Context */
    protected $objContext;
    /** @var  JsResponse */
    protected $objJsResponse;


    /**
     * Return true if all output should be minimized. Useful for production environments when you are trying to reduce
     * the amount of raw text you are sending to a browser. Minimize generally removes space and space-like characters.
     *
     * @return bool
     */
    public function minimize()
    {
        if (defined('__MINIMIZE__') && __MINIMIZE__) {
            return true;
        }
        return false;
    }

    /**
     * Returns the singleton instance of the context, which has information about the environment the current script
     * is running in--things like: is it running in command line mode, or what are the server parameters if running
     * in response to an HTTP request, etc.
     *
     * @return Context
     */
    public function context()
    {
        if (!$this->objContext) {
            $this->objContext = new Context();
        }
        return $this->objContext;
    }

    /**
     * Returns a singleton jsResponse object. This is for internal use of the application class only. It manages
     * the javascript and json responses to requests.
     *
     * @return JsResponse
     */
    protected function jsResponse()
    {
        if (!$this->objJsResponse) {
            $this->objJsResponse = new JsResponse();
        }
        return $this->objJsResponse;
    }

    /**
     * @return string   The application encoding type.
     */
    public function encodingType()
    {
        assert(defined('__APPLICATION_ENCODING_TYPE__')); // Must be defined
        return __APPLICATION_ENCODING_TYPE__;
    }

    /**
     * @return string   The current docroot setting
     */
    public function docRoot()
    {
        return trim(__DOCROOT__);
    }

    public static function isAjax()
    {
        return Application::instance()->context()->requestMode() == Context::REQUEST_MODE_AJAX;
    }

    /**
     * This is called by the PHP5 Autoloader.  This static method can be overridden.
     *
     *
     * @param $strClassName
     * @return boolean whether or not a class was found / included
     */
    public static function autoload($strClassName)
    {
        if (file_exists($strFilePath = sprintf('%s/plugins/%s.php', __INCLUDES__, $strClassName))) {
            require_once($strFilePath);
            return true;
        }
        return false;
    }

    /**
     * Temprorarily overrides the default error handling mechanism.  Remember to call
     * RestoreErrorHandler to restore the error handler back to the default.
     *
     * @param string $strName the name of the new error handler function, or NULL if none
     * @param integer $intLevel if a error handler function is defined, then the new error reporting level (if any)
     *
     * @deprecated Create a \QCubed\Error\Handler instead
     * @throws Caller
     */
    public static function setErrorHandler($strName, $intLevel = null)
    {
        throw new Caller("SetErrorHandler is deprecated. Create an Error\\Handler instead.");
    }

    /**
     * Restores the temporarily overridden default error handling mechanism back to the default.
     */
    public static function restoreErrorHandler()
    {
        throw new Caller("SetErrorHandler is deprecated. Create an Error\\Handler instead.");
    }

    /**
     * This will redirect the user to a new web location.  This can be a relative or absolute web path, or it
     * can be an entire URL.
     *
     * TODO: break this into two routines, since the resulting UI behavior is really different. Redirect and LoadPage??
     *
     * @param string $strLocation target patch
     * @param bool $blnAbortCurrentScript Whether to abort the current script, or finish it out so data gets saved.
     * @return void
     */
    public static function redirect($strLocation, $blnAbortCurrentScript = true)
    {
        if (!$blnAbortCurrentScript) {
            // Use the javascript command mechanism
            Application::instance()->jsResponse()->setLocation($strLocation);
        } else {
            /** \QCubed\Project\Control\Form */
            global $_FORM;

            if ($_FORM) {
                $_FORM->saveControlState();
            }

            // Clear the output buffer (if any)
            ob_clean();

            if (Application::isAjax() ||
                (array_key_exists('Qform__FormCallType', $_POST) &&
                    ($_POST['Qform__FormCallType'] == QCallType::Ajax))
            ) {
                Application::sendAjaxResponse(array(JsResponse::LOCATION => $strLocation));
            } else {
                // Was "DOCUMENT_ROOT" set?
                if (array_key_exists('DOCUMENT_ROOT', $_SERVER) && ($_SERVER['DOCUMENT_ROOT'])) {
                    // If so, we're likely using PHP as a Plugin/Module
                    // Use 'header' to redirect
                    header(sprintf('Location: %s', $strLocation));
                } else {
                    // We're likely using this as a CGI
                    // Use JavaScript to redirect
                    printf('<script type="text/javascript">document.location = "%s";</script>', $strLocation);
                }
            }

            // End the Response Script
            session_write_close();
            exit();
        }
    }


    /**
     * This will close the window.
     *
     * @param bool $blnAbortCurrentScript Whether to abort the current script, or finish it out so data gets saved.
     * @return void
     */
    public static function closeWindow($blnAbortCurrentScript = false)
    {
        if (!$blnAbortCurrentScript) {
            // Use the javascript command mechanism
            Application::instance()->jsResponse()->closeWindow();
        } else {
            // Clear the output buffer (if any)
            ob_clean();

            if (Application::isAjax()) {
                // AJAX-based Response
                Application::sendAjaxResponse(array(JsResponse::CLOSE => 1));
            } else {
                // Use JavaScript to close
                _p('<script type="text/javascript">window.close();</script>', false);
            }

            // End the Response Script
            exit();
        }
    }

    /**
     * Set a cookie. Allows setting of cookies in responses to ajax requests.
     *
     * @param string $strName
     * @param string $strValue
     * @param QDateTime $dttTimeout
     * @param string $strPath
     * @param null|string $strDomain
     * @param bool $blnSecure
     */
    public static function setCookie(
        $strName,
        $strValue,
        QDateTime $dttTimeout,
        $strPath = '/',
        $strDomain = null,
        $blnSecure = false
    ) {
        if (QApplication::$RequestMode == QRequestMode::Ajax) {
            self::executeJsFunction('qcubed.setCookie',
                $strName,
                $strValue,
                $dttTimeout,
                $strPath,
                $strDomain,
                $blnSecure);
        } else {
            setcookie($strName, $strValue, $dttTimeout->Timestamp, $strPath, $strDomain, $blnSecure);
        }
    }

    /**
     * Delete's the given cookie IF its set. In other words, you cannot set a cookie and then delete a cookie right away before the
     * cookie gets sent to the browser.
     *
     * @param $strName
     */
    public static function deleteCookie($strName)
    {
        if (isset($_COOKIE[$strName])) { // don't post a cookie if its not set
            $dttTimeout = QDateTime::now();
            $dttTimeout->addYears(-5);

            self::setCookie($strName, "", $dttTimeout);
        }
    }


    /**
     * By default, this is used by the codegen and form drafts to do a quick check
     * on the ALLOW_REMOTE_ADMIN constant (as defined in configuration.inc.php).  If enabled,
     * then anyone can access the page.  If disabled, only "localhost" can access the page.
     * If you want to run a script that should be accessible regardless of
     * ALLOW_REMOTE_ADMIN, simply remove the CheckRemoteAdmin() method call from that script.
     *
     * @throws QRemoteAdminDeniedException
     * @return void
     */
    public static function checkRemoteAdmin()
    {
        if (!QApplication::isRemoteAdminSession()) {
            return;
        }

        // If we're here -- then we're not allowed to access.  Present the Error/Issue.
        header($_SERVER['SERVER_PROTOCOL'] . ' 401 Access Denied');
        header('Status: 401 Access Denied', true);

        throw new QRemoteAdminDeniedException();
    }

    /**
     * Checks whether the current request was made by an ADMIN
     * This does not refer to your Database admin or an Admin user defined in your application but an IP address
     * (or IP address range) defined in configuration.inc.php.
     *
     * The function can be used to restrict access to sensitive pages to a list of IPs (or IP ranges), such as the LAN to which
     * the server hosting the QCubed application is connected.
     * @static
     * @return bool
     */
    public static function isRemoteAdminSession()
    {
        // Allow Remote?
        if (ALLOW_REMOTE_ADMIN === true) {
            return false;
        }

        // Are we localhost?
        if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1') {
            return false;
        }

        // Are we the correct IP?
        if (is_string(ALLOW_REMOTE_ADMIN)) {
            foreach (explode(',', ALLOW_REMOTE_ADMIN) as $strIpAddress) {
                if (QApplication::isIPInRange($_SERVER['REMOTE_ADDR'], $strIpAddress) ||
                    (array_key_exists('HTTP_X_FORWARDED_FOR',
                            $_SERVER) && (QApplication::isIPInRange($_SERVER['HTTP_X_FORWARDED_FOR'], $strIpAddress)))
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks whether the given IP falls into the given IP range
     * @static
     * @param string $ip the IP number to check
     * @param string $range the IP number range. The range could be in 'IP/mask' or 'IP - IP' format. mask could be a simple
     * integer or a dotted netmask.
     * @return bool
     */
    public static function isIPInRange($ip, $range)
    {
        $ip = trim($ip);
        if (strpos($range, '/') !== false) {
            // we are given a IP/mask
            list($net, $mask) = explode('/', $range);
            $net = ip2long(trim($net));
            $mask = trim($mask);
            //$ip_net = ip2long($net);
            if (strpos($mask, '.') !== false) {
                // mask has the dotted notation
                $ip_mask = ip2long($mask);
            } else {
                // mask is an integer
                $ip_mask = ~((1 << (32 - $mask)) - 1);
            }
            $ip = ip2long($ip);
            return ($net & $ip_mask) == ($ip & $ip_mask);
        }
        if (strpos($range, '-') !== false) {
            // we are given an IP - IP range
            list($first, $last) = explode('-', $range);
            $first = ip2long(trim($first));
            $last = ip2long(trim($last));
            $ip = ip2long($ip);
            return $first <= $ip && $ip <= $last;
        }

        // $range is a simple IP
        return $ip == trim($range);
    }


    /**
     * Causes the browser to display a JavaScript alert() box with supplied message
     * @param string $strMessage Message to be displayed
     */
    public static function displayAlert($strMessage)
    {
        Application::instance()->jsResponse()->displayAlert($strMessage);
    }

    /**
     * This class can be used to call a Javascript function in the client browser from the server side.
     * Can be used inside event handlers to do something after verification  on server side.
     *
     * TODO: Since this is implemented with an "eval" on the client side in ajax, we should phase this out in favor
     * of specific commands sent to the client.
     *
     * @static
     * @deprecated Will be eventually removed. If you need to do something in javascript, add it to QAjaxResponse.
     * @param string $strJavaScript the javascript to execute
     * @param string $strPriority
     * @throws QCallerException
     */
    public static function executeJavaScript($strJavaScript, $strPriority = QJsPriority::Standard)
    {
        Application::instance()->jsResponse()->executeJavaScript($strJavaScript, $strPriority);
    }

    /**
     * Execute a function on a particular control. Many javascript widgets are structured this way, and this gives us
     * a general purpose way of sending commands to widgets without an 'eval' on the client side.
     *
     * Commands will be executed in the order received, along with ExecuteJavaScript commands and ExecuteObjectCommands.
     * If you want to force a command to execute first, give it high priority, or last, give it low priority.
     *
     * @param string $strControlId Id of control to direct the command to.
     * @param string $strFunctionName Function name to call. For jQueryUI, this would be the widget name
     * @param string $strFunctionName,... Unlimited OPTIONAL parameters to use as a parameter list to the function. List can
     *                                        end with a QJsPriority to prioritize the command.
     */
    public static function executeControlCommand($strControlId, $strFunctionName /*, ..., QJsPriority */)
    {
        $args = func_get_args();
        call_user_func_array([Application::instance()->jsResponse(), 'executeControlCommand'], $args);
    }

    /**
     * Call a function on a jQuery selector. The selector can be a single string, or an array where the first
     * item is a selector specifying the items within the context of the second selector.
     *
     * @param array|string $mixSelector
     * @param string $strFunctionName
     * @param string $strFunctionName,... Unlimited OPTIONAL parameters to use as a parameter list to the function. List can
     *                                        end with a QJsPriority to prioritize the command.
     * @throws QCallerException
     */
    public static function executeSelectorFunction($mixSelector, $strFunctionName /*, ..., QJsPriority */)
    {
        $args = func_get_args();
        call_user_func_array([Application::instance()->jsResponse(), 'executeSelectorFunction'], $args);
    }


    /**
     * Call the given function with the given arguments. If just a function name, then the window object is searched.
     * The function can be inside an object accessible from the global namespace by separating with periods.
     * @param string $strFunctionName Can be namespaced, as in "qcubed.func".
     * @param string $strFunctionName,... Unlimited OPTIONAL parameters to use as a parameter list to the function. List can
     *                                        end with a QJsPriority to prioritize the command.
     */
    public static function executeJsFunction($strFunctionName /*, ... */)
    {
        $args = func_get_args();
        call_user_func_array([Application::instance()->jsResponse(), 'executeJsFunction'], $args);
    }

    /**
     * One time add of style sheets, to be used by QForm only for last minute style sheet injection.
     * @param string[] $strStyleSheetArray
     */
    public static function addStyleSheets(array $strStyleSheetArray)
    {
        Application::instance()->jsResponse()->addStyleSheets($strStyleSheetArray);
    }

    /**
     * Add an array of javascript files for one-time inclusion. Called by QForm. Do not call.
     * @param string[] $strJavaScriptFileArray
     */
    public static function addJavaScriptFiles($strJavaScriptFileArray)
    {
        Application::instance()->jsResponse()->addJavaScriptFiles($strJavaScriptFileArray);
    }

    /**
     * Outputs the current page with the buffer data
     * @param string $strBuffer Buffer data
     *
     * @return string
     */
    public static function outputPage($strBuffer)
    {
        // If the ProcessOutput flag is set to false, simply return the buffer
        // without processing anything.
        if (!QApplication::$ProcessOutput) {
            return $strBuffer;
        }

        if (QApplication::$ErrorFlag) {
            return $strBuffer;
        } else {
            if (Application::isAjax()) {
                return trim($strBuffer);
            } else {
                // Update Cache-Control setting
                header('Cache-Control: ' . QApplication::$CacheControl);
                // make sure the server does not override the character encoding value by explicitly sending it out as a header.
                // some servers will use an internal default if not specified in the header, and that will override the "encoding" value sent in the text.
                header(sprintf('Content-Type: %s; charset=%s', strtolower(QApplication::$ContentType),
                    strtolower(Application::instance()->encodingType())));

                /*
                 * Normally, FormBase->RenderEnd will render the javascripts. In the unusual case
                 * of not rendering with a QForm object, this will still output embedded javascript commands.
                 */
                $strScript = Application::instance()->jsResponse()->renderJavascript();
                if ($strScript) {
                    return $strBuffer . '<script type="text/javascript">' . $strScript . '</script>';
                }

                return $strBuffer;
            }
        }
    }

    public static function startOutputBuffering()
    {
        if (php_sapi_name() !== 'cli' &&    // Do not buffer the command line interface
            !defined('__NO_OUTPUT_BUFFER__')
        ) {
            ob_start('QApplicationBase::EndOutputBuffering');
        }
    }

    public static function endOutputBuffering($strBuffer)
    {
        return QApplication::outputPage($strBuffer);
    }


    /**
     * Render scripts for injecting files into the html output. This is for server only, not ajax.
     * This list will appear ahead of the javascript commands rendered below.
     *
     * @static
     * @return string
     */
    public static function renderFiles()
    {
        return Application::instance()->jsResponse()->renderFiles();
    }

    /**
     * Function renders all the Javascript commands as output to the client browser. This is a mirror of what
     * occurs in the success function in the qcubed.js ajax code.
     *
     * @param bool $blnBeforeControls True to only render the javascripts that need to come before the controls are defined.
     *                                This is used to break the commands issued into two groups.
     * @static
     * @return string
     */
    public static function renderJavascript($blnBeforeControls = false)
    {
        return Application::instance()->jsResponse()->renderJavascript($blnBeforeControls);
    }

    /**
     * Return the javascript command array, for use by form ajax response. Will erase the command array, so
     * the form better use it.
     * @static
     * @return array
     */
    public static function getJavascriptCommandArray()
    {
        return Application::instance()->jsResponse()->getJavascriptCommandArray();
    }


    /**
     * Print an ajax response to the browser.
     *
     * @param array $strResponseArray An array keyed with QAjaxResponse items. These items will be read by the qcubed.js
     * ajax success function and operated on. The goals is to eventually have all possible response types represented
     * in the QAjaxResponse so that we can remove the "eval" in qcubed.js.
     */
    public static function sendAjaxResponse(array $strResponseArray)
    {
        header('Content-Type: text/json'); // not application/json, as IE reportedly blows up on that, but jQuery knows what to do.
        $strJSON = Js\Helper::toJSON($strResponseArray);
        if (Application::instance()->encodingType() && Application::instance()->encodingType() != 'UTF-8') {
            $strJSON = iconv(Application::instance()->encodingType(), 'UTF-8', $strJSON); // json must be UTF-8 encoded
        }
        print($strJSON);
    }


    /**
     * Utility function to get the JS file URI, given a string input
     * @param string $strFile File name to be tested
     *
     * @return string the final JS file URI
     */
    public static function getJsFileUri($strFile)
    {
        if ((strpos($strFile, "http") === 0) || (strpos($strFile, "https") === 0)) {
            return $strFile;
        }
        if (strpos($strFile, "/") === 0) {
            return __VIRTUAL_DIRECTORY__ . $strFile;
        }
        return __VIRTUAL_DIRECTORY__ . __JS_ASSETS__ . '/' . $strFile;
    }

    /**
     * Utility function to get the CSS file URI, given a string input
     * @param string $strFile File name to be tested
     *
     * @return string the final CSS URI
     */
    public static function getCssFileUri($strFile)
    {
        if ((strpos($strFile, "http") === 0) || (strpos($strFile, "https") === 0)) {
            return $strFile;
        }
        if (strpos($strFile, "/") === 0) {
            return __VIRTUAL_DIRECTORY__ . $strFile;
        }
        return __VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/' . $strFile;
    }

    /**
     * For development purposes, this static method outputs all the Application static variables
     *
     * @return void
     */
    public static function varDump()
    {
        _p('<div class="var-dump"><strong>QCubed Settings</strong><ul>', false);
        $arrValidationErrors = QInstallationValidator::validate();
        foreach ($arrValidationErrors as $objResult) {
            printf('<li><strong class="warning">WARNING:</strong> %s</li>', $objResult->strMessage);
        }

        printf('<li>QCUBED_VERSION = "%s"</li>', QCUBED_VERSION);
        printf('<li>jQuery version = "%s"</li>', __JQUERY_CORE_VERSION__);
        printf('<li>jQuery UI version = "%s"</li>', __JQUERY_UI_VERSION__);
        printf('<li>__SUBDIRECTORY__ = "%s"</li>', __SUBDIRECTORY__);
        printf('<li>__VIRTUAL_DIRECTORY__ = "%s"</li>', __VIRTUAL_DIRECTORY__);
        printf('<li>__INCLUDES__ = "%s"</li>', __INCLUDES__);
        printf('<li>__QCUBED_CORE__ = "%s"</li>', __QCUBED_CORE__);
        printf('<li>ERROR_PAGE_PATH = "%s"</li>', ERROR_PAGE_PATH);
        printf('<li>PHP Include Path = "%s"</li>', get_include_path());
        printf('<li>DocumentRoot = "%s"</li>', Application::instance()->docRoot());
        printf('<li>QApplication::$EncodingType = "%s"</li>', Application::instance()->encodingType());
        printf('<li>QApplication::$PathInfo = "%s"</li>', Application::instance()->context()->pathInfo());
        printf('<li>QueryString = "%s"</li>', Application::instance()->context()->queryString());
        printf('<li>RequestUri = "%s"</li>', Application::instance()->context()->requestUri());
        printf('<li>QApplication::$ScriptFilename = "%s"</li>', Application::instance()->context()->scriptFileName());
        printf('<li>QApplication::$ScriptName = "%s"</li>', Application::instance()->context()->scriptName());
        printf('<li>ServerAddress = "%s"</li>', Application::instance()->context()->serverAddress());

        if (DatabaseService::isInitialized()) {
            for ($intKey = 1; $intKey <= DatabaseService::count(); $intKey++) {
                printf('<li>Database[%s] settings:</li>', $intKey);
                _p("<ul>", false);
                foreach (unserialize(constant('DB_CONNECTION_' . $intKey)) as $key => $value) {
                    if ($key == "password") {
                        $value = "hidden for security purposes";
                    }

                    _p("<li>" . $key . " = " . var_export($value, true) . "</li>", false);
                }
                _p("</ul>", false);
            }
        }
        _p('</ul></div>', false);
    }
}
