<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed;

/**
 * This abstract class should never be instantiated.  It contains static methods,
 * variables and constants to be used throughout the application.
 *
 * The static method "Initialize" should be called at the begin of the script by
 * prepend.inc.
 */
class ApplicationBase extends QBaseClass
{
    //////////////////////////
    // Public Static Variables
    //////////////////////////

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
    public static $DefaultCrossScriptingMode = QCrossScripting::Legacy;

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

    /**
     * Return true if all output should be minimized. Useful for production environments when you are trying to reduce
     * the amount of raw text you are sending to a browser. Minimize generally removes space and space-like characters.
     *
     * @return bool
     */
    public function minimize() {
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
    public function context() {
        if (!$this->objContext) {
            $this->objContext = new Context();
        }
        return $this->objContext;
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



    public static function SessionOverride()
    {
        // Are we using QDbBackedSessionHandler?
        if (defined("DB_BACKED_SESSION_HANDLER_DB_INDEX") &&
            constant("DB_BACKED_SESSION_HANDLER_DB_INDEX") != 0 && defined("DB_BACKED_SESSION_HANDLER_TABLE_NAME")
        ) {
            // Yes we are going to override PHP's default file based handlers.
            QDbBackedSessionHandler::Initialize(DB_BACKED_SESSION_HANDLER_DB_INDEX,
                DB_BACKED_SESSION_HANDLER_TABLE_NAME);
        }
    }

    /**
     * This is called by the PHP5 Autoloader.  This static method can be overridden.
     *
     * @param $strClassName
     * @return boolean whether or not a class was found / included
     */
    public static function Autoload($strClassName)
    {
        if (isset(QApplication::$ClassFile[strtolower($strClassName)])) {
            require_once(QApplication::$ClassFile[strtolower($strClassName)]);
            return true;
        } else {
            if (file_exists($strFilePath = sprintf('%s/%s.class.php', __INCLUDES__, $strClassName))) {
                require_once($strFilePath);
                return true;
            } else {
                if (file_exists($strFilePath = sprintf('%s/controls/%s.class.php', __INCLUDES__, $strClassName))) {
                    require_once($strFilePath);
                    return true;
                } else {
                    if (file_exists($strFilePath = sprintf('%s/plugins/%s.php', __INCLUDES__, $strClassName))) {
                        require_once($strFilePath);
                        return true;
                    } else {
                        if (false !== ($intStart = strpos($strClassName, 'QCubed\\Plugin\\'))) {
                            $strClassName = substr($strClassName, $intStart + 14);
                            if (file_exists($strFilePath = sprintf('%s/plugins/%s.php', __INCLUDES__, $strClassName))) {
                                require_once($strFilePath);
                                return true;
                            }
                        }
                    }
                }
            }
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
     * @throws QCallerException
     */
    public static function SetErrorHandler($strName, $intLevel = null)
    {
        if (!is_null(QApplicationBase::$intStoredErrorLevel)) {
            throw new QCallerException('Error handler is already currently overridden.  Cannot override twice.  Call RestoreErrorHandler before calling SetErrorHandler again.');
        }
        if (!$strName) {
            // No Error Handling is wanted -- simulate a "On Error, Resume" type of functionality
            set_error_handler('QcubedHandleError', 0);
            QApplicationBase::$intStoredErrorLevel = error_reporting(0);
        } else {
            set_error_handler($strName, $intLevel);
            QApplicationBase::$intStoredErrorLevel = -1;
        }
    }

    /**
     * Restores the temporarily overridden default error handling mechanism back to the default.
     */
    public static function RestoreErrorHandler()
    {
        if (is_null(QApplicationBase::$intStoredErrorLevel)) {
            throw new QCallerException('Error handler is not currently overridden.  Cannot reset something that was never overridden.');
        }
        if (QApplicationBase::$intStoredErrorLevel != -1) {
            error_reporting(QApplicationBase::$intStoredErrorLevel);
        }
        restore_error_handler();
        QApplicationBase::$intStoredErrorLevel = null;
    }

    /** @var null|int Stored Error Level (used for Settings and Restoring error handler) */
    private static $intStoredErrorLevel = null;

    /**
     * Create a directory on file system
     *
     * @param string $strPath Path of the directory to be created
     * @param null|int $intMode Octal representation of permissions ('0755' style)
     *
     * @return bool
     */
    public static function MakeDirectory($strPath, $intMode = null)
    {
        return QFolder::MakeDirectory($strPath, $intMode);
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
    public static function Redirect($strLocation, $blnAbortCurrentScript = true)
    {

        if (!$blnAbortCurrentScript) {
            // Use the javascript command mechanism
            QApplication::$JavascriptCommandArray[QAjaxResponse::Location] = $strLocation;
        } else {
            global $_FORM;

            if ($_FORM) {
                $_FORM->SaveControlState();
            }

            // Clear the output buffer (if any)
            ob_clean();

            if ((QApplication::$RequestMode == QRequestMode::Ajax) ||
                (array_key_exists('Qform__FormCallType', $_POST) &&
                    ($_POST['Qform__FormCallType'] == QCallType::Ajax))
            ) {
                QApplication::SendAjaxResponse(array(QAjaxResponse::Location => $strLocation));
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
    public static function CloseWindow($blnAbortCurrentScript = false)
    {
        if (!$blnAbortCurrentScript) {
            // Use the javascript command mechanism
            QApplication::$JavascriptCommandArray[QAjaxResponse::Close] = true;
        } else {
            // Clear the output buffer (if any)
            ob_clean();

            if (QApplication::$RequestMode == QRequestMode::Ajax) {
                // AJAX-based Response
                $aResponse[QAjaxResponse::Close] = 1;
                QApplication::SendAjaxResponse($aResponse);
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
     * @param sring $strValue
     * @param QDatTime $dttTimeout
     * @param string $strPath
     * @param null|string $strDomain
     * @param bool $blnSecure
     */
    public static function SetCookie(
        $strName,
        $strValue,
        QDateTime $dttTimeout,
        $strPath = '/',
        $strDomain = null,
        $blnSecure = false
    ) {
        if (QApplication::$RequestMode == QRequestMode::Ajax) {
            self::ExecuteJsFunction('qcubed.setCookie', $strName, $strValue, $dttTimeout, $strPath, $strDomain,
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
    public static function DeleteCookie($strName)
    {
        if (isset($_COOKIE[$strName])) { // don't post a cookie if its not set
            $dttTimeout = QDateTime::Now();
            $dttTimeout->AddYears(-5);

            self::SetCookie($strName, "", $dttTimeout);
        }
    }

    /**
     * Generates a valid URL Query String based on values in the provided array. If no array is provided, it uses the global $_GET
     * @param array $arr
     * @return string
     */
    public static function GenerateQueryString($arr = null)
    {
        if (null === $arr) {
            $arr = $_GET;
        }
        if (count($arr)) {
            $strToReturn = '';
            foreach ($arr as $strKey => $mixValue) {
                $strToReturn .= QApplication::GenerateQueryStringHelper(urlencode($strKey), $mixValue);
            }
            return '?' . substr($strToReturn, 1);
        } else {
            return '';
        }
    }

    /**
     * Generates part of query string (helps in generating the complete query string)
     * @param string $strKey Key for the query string
     * @param string|integer|array $mixValue Value we have to put as the value of the key
     *
     * @return null|string
     */
    protected static function GenerateQueryStringHelper($strKey, $mixValue)
    {
        if (is_array($mixValue)) {
            $strToReturn = null;
            foreach ($mixValue as $strSubKey => $mixSubValue) {
                $strToReturn .= QApplication::GenerateQueryStringHelper($strKey . '[' . $strSubKey . ']', $mixSubValue);
            }
            return $strToReturn;
        } else {
            return '&' . $strKey . '=' . urlencode($mixValue);
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
    public static function CheckRemoteAdmin()
    {
        if (!QApplication::IsRemoteAdminSession()) {
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
    public static function IsRemoteAdminSession()
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
                if (QApplication::IsIPInRange($_SERVER['REMOTE_ADDR'], $strIpAddress) ||
                    (array_key_exists('HTTP_X_FORWARDED_FOR',
                            $_SERVER) && (QApplication::IsIPInRange($_SERVER['HTTP_X_FORWARDED_FOR'], $strIpAddress)))
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
    public static function IsIPInRange($ip, $range)
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
     * If this particular item is set, we ensure that this command, and only this command will get invoked on the
     * next response. The rest of the commands will wait until the next response.
     *
     * @var null|array;
     */
    public static $JavascriptExclusiveCommand = null;

    /** @var array A structured array of commands to be sent to either the ajax response, or page output.
     * Replaces the AlertMessageArray, JavaScriptArray, JavaScriptArrayHighPriority, and JavaScriptArrayLowPriority.
     */
    protected static $JavascriptCommandArray = array();

    /** @var array JS files to be added to the list of files in front of the javascript commands. Should include jquery, etc. */
    protected static $JavascriptFileArray = array();

    /*
            public static $AlertMessageArray = array();
            public static $JavaScriptArray = array();
            public static $JavaScriptArrayHighPriority = array();
            public static $JavaScriptArrayLowPriority = array();
            public static $ControlCommands = array();*/

    /** @var bool Used to determine if an error has occurred */
    public static $ErrorFlag = false;

    /**
     * Causes the browser to display a JavaScript alert() box with supplied message
     * @param string $strMessage Message to be displayed
     */
    public static function DisplayAlert($strMessage)
    {
        QApplication::$JavascriptCommandArray[QAjaxResponse::Alert][] = $strMessage;
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
    public static function ExecuteJavaScript($strJavaScript, $strPriority = QJsPriority::Standard)
    {
        if (is_bool($strPriority)) {
            //we keep this codepath for backward compatibility
            if ($strPriority === true) {
                throw new QCallerException('Please specify a correct priority value');
            }
        } else {
            switch ($strPriority) {
                case QJsPriority::High:
                    QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh][] = ['script' => $strJavaScript];
                    break;
                case QJsPriority::Low:
                    QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsLow][] = ['script' => $strJavaScript];
                    break;
                case QJsPriority::Exclusive:
                    QApplication::$JavascriptExclusiveCommand = ['script' => $strJavaScript];
                    break;
                default:
                    QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium][] = ['script' => $strJavaScript];
                    break;
            }
        }
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
    public static function ExecuteControlCommand($strControlId, $strFunctionName /*, ..., QJsPriority */)
    {
        $args = func_get_args();
        $args[0] = '#' . $strControlId;
        call_user_func_array('QApplication::ExecuteSelectorFunction', $args);
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
    public static function ExecuteSelectorFunction($mixSelector, $strFunctionName /*, ..., QJsPriority */)
    {
        if (!(is_string($mixSelector) || (is_array($mixSelector) && count($mixSelector) == 2))) {
            throw new QCallerException ('Selector must be a string or an array of two items');
        }
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        if ($args && end($args) === QJsPriority::High) {
            $code = QAjaxResponse::CommandsHigh;
            array_pop($args);
        } elseif ($args && end($args) === QJsPriority::Low) {
            $code = QAjaxResponse::CommandsLow;
            array_pop($args);
        } elseif ($args && end($args) === QJsPriority::Exclusive) {
            array_pop($args);
            QApplication::$JavascriptExclusiveCommand = [
                'selector' => $mixSelector,
                'func' => $strFunctionName,
                'params' => $args
            ];
            return;
        } elseif ($args && end($args) === QJsPriority::Last) {
            array_pop($args);
            QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsFinal][] = [
                'selector' => $mixSelector,
                'func' => $strFunctionName,
                'params' => $args,
                'final' => true
            ];
            return;
        } else {
            $code = QAjaxResponse::CommandsMedium;
        }
        if (empty($args)) {
            $args = null;
        }

        QApplication::$JavascriptCommandArray[$code][] = [
            'selector' => $mixSelector,
            'func' => $strFunctionName,
            'params' => $args
        ];
    }


    /**
     * Call the given function with the given arguments. If just a function name, then the window object is searched.
     * The function can be inside an object accessible from the global namespace by separating with periods.
     * @param string $strFunctionName Can be namespaced, as in "qcubed.func".
     * @param string $strFunctionName,... Unlimited OPTIONAL parameters to use as a parameter list to the function. List can
     *                                        end with a QJsPriority to prioritize the command.
     */
    public static function ExecuteJsFunction($strFunctionName /*, ... */)
    {
        $args = func_get_args();
        array_shift($args);
        if ($args && end($args) === QJsPriority::High) {
            $code = QAjaxResponse::CommandsHigh;
            array_pop($args);
        } elseif ($args && end($args) === QJsPriority::Low) {
            $code = QAjaxResponse::CommandsLow;
            array_pop($args);
        } elseif ($args && end($args) === QJsPriority::Exclusive) {
            array_pop($args);
            QApplication::$JavascriptExclusiveCommand = ['func' => $strFunctionName, 'params' => $args];
            return;
        } else {
            $code = QAjaxResponse::CommandsMedium;
        }
        if (empty($args)) {
            $args = null;
        }

        QApplication::$JavascriptCommandArray[$code][] = ['func' => $strFunctionName, 'params' => $args];
    }

    /**
     * One time add of style sheets, to be used by QForm only for last minute style sheet injection.
     * @param string[] $strStyleSheetArray
     */
    public static function AddStyleSheets(array $strStyleSheetArray)
    {
        if (empty(QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets])) {
            QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets] = $strStyleSheetArray;
        } else {
            QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets] =
                array_merge(QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets], $strStyleSheetArray);
        }
    }

    /**
     * Add an array of javascript files for one-time inclusion. Called by QForm. Do not call.
     * @param string[] $strJavaScriptFileArray
     */
    public static function AddJavaScriptFiles($strJavaScriptFileArray)
    {
        if (empty(QApplication::$JavascriptFileArray[QAjaxResponse::JavaScripts])) {
            QApplication::$JavascriptFileArray[QAjaxResponse::JavaScripts] = $strJavaScriptFileArray;
        } else {
            QApplication::$JavascriptFileArray[QAjaxResponse::JavaScripts] =
                array_merge(QApplication::$JavascriptFileArray[QAjaxResponse::JavaScripts], $strJavaScriptFileArray);
        }
    }

    /**
     * Outputs the current page with the buffer data
     * @param string $strBuffer Buffer data
     *
     * @return string
     */
    public static function OutputPage($strBuffer)
    {
        // If the ProcessOutput flag is set to false, simply return the buffer
        // without processing anything.
        if (!QApplication::$ProcessOutput) {
            return $strBuffer;
        }

        if (QApplication::$ErrorFlag) {
            return $strBuffer;
        } else {
            if (QApplication::$RequestMode == QRequestMode::Ajax) {
                return trim($strBuffer);
            } else {
                // Update Cache-Control setting
                header('Cache-Control: ' . QApplication::$CacheControl);
                // make sure the server does not override the character encoding value by explicitly sending it out as a header.
                // some servers will use an internal default if not specified in the header, and that will override the "encoding" value sent in the text.
                header(sprintf('Content-Type: %s; charset=%s', strtolower(QApplication::$ContentType),
                    strtolower($this->encodingType())));

                /*
                 * Normally, FormBase->RenderEnd will render the javascripts. In the unusual case
                 * of not rendering with a QForm object, this will still output embedded javascript commands.
                 */
                $strScript = QApplicationBase::RenderJavascript();
                if ($strScript) {
                    return $strBuffer . '<script type="text/javascript">' . $strScript . '</script>';
                }

                return $strBuffer;
            }
        }
    }

    public static function StartOutputBuffering()
    {
        if (php_sapi_name() !== 'cli' &&    // Do not buffer the command line interface
            !defined('__NO_OUTPUT_BUFFER__')
        ) {

            ob_start('QApplicationBase::EndOutputBuffering');
        }
    }

    public static function EndOutputBuffering($strBuffer)
    {
        return QApplication::OutputPage($strBuffer);
    }


    /**
     * Render scripts for injecting files into the html output. This is for server only, not ajax.
     * This list will appear ahead of the javascript commands rendered below.
     *
     * @static
     * @return string
     */
    public static function RenderFiles()
    {
        $strScript = '';

        // Javascript files should get processed before the commands.
        if (!empty(QApplication::$JavascriptFileArray[QAjaxResponse::JavaScripts])) {
            foreach (QApplication::$JavascriptFileArray[QAjaxResponse::JavaScripts] as $js) {
                $strScript .= sprintf('<script type="text/javascript" src="%s"></script>',
                        QApplication::GetJsFileUri($js)) . "\n";
            }
        }

        QApplication::$JavascriptFileArray = array();

        return $strScript;
    }

    /**
     * Function renders all the Javascript commands as output to the client browser. This is a mirror of what
     * occurs in the success function in the qcubed.js ajax code.
     *
     * @param $blnBeforeControls    True to only render the javascripts that need to come before the controls are defined.
     *                                This is used to break the commands issued into two groups.
     * @static
     * @return string
     */
    public static function RenderJavascript($blnBeforeControls = false)
    {
        $strScript = '';

        // Style sheet injection by a control. Not very common, as other ways of adding style sheets would normally be done first.
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets])) {
            $str = '';
            foreach (QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets] as $ss) {
                $str .= 'qc.loadStyleSheetFile("' . $ss . '", "all"); ';
            }
            QApplication::$JavascriptCommandArray[QAjaxResponse::StyleSheets] = null;
        }

        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::Alert])) {
            foreach (QApplication::$JavascriptCommandArray[QAjaxResponse::Alert] as $strAlert) {
                $strAlert = json_encode($strAlert);
                $strScript .= sprintf('alert(%s); ', $strAlert);
            }
            QApplication::$JavascriptCommandArray[QAjaxResponse::Alert] = null;
        }

        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh])) {
            $strScript .= self::RenderCommandArray(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh]);
            QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh] = null;
        }

        if ($blnBeforeControls) {
            return $strScript;
        }    // When we call again, everything above here will be skipped since we are emptying the arrays

        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium])) {
            $strScript .= self::RenderCommandArray(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium]);
            QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium] = null;
        }

        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsLow])) {
            $strScript .= self::RenderCommandArray(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsLow]);
        }

        // A QApplication::Redirect
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::Location])) {
            $strLocation = QApplication::$JavascriptCommandArray[QAjaxResponse::Location];
            $strScript .= sprintf('document.location = "%s";', $strLocation);
        }
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::Close])) {
            $strScript .= 'window.close();';
        }

        QApplication::$JavascriptCommandArray = array();

        return $strScript;
    }

    private static function RenderCommandArray(array $commandArray)
    {
        $strScript = '';
        foreach ($commandArray as $command) {
            if (isset($command['script'])) {    // a script to use eval on
                $strScript .= sprintf('%s;', $command['script']) . _nl();
            } elseif (isset($command['selector'])) {    // a control function
                if (is_array($command['selector'])) {
                    $strSelector = sprintf('"%s", "%s"', $command['selector'][0], $command['selector'][1]);
                } else {
                    $strSelector = '"' . $command['selector'] . '"';
                }

                if ($params = $command['params']) {
                    $objParams = new QJsParameterList($params);
                    $strParams = $objParams->toJsObject();
                } else {
                    $strParams = '';
                }
                $strScript .= sprintf('jQuery(%s).%s(%s);', $strSelector, $command['func'], $strParams) . _nl();
            } elseif (isset($command['func'])) {    // a function call
                if ($params = $command['params']) {
                    $objParams = new QJsParameterList($params);
                    $strParams = $objParams->toJsObject();
                } else {
                    $strParams = '';
                }
                $strScript .= sprintf('%s(%s);', $command['func'], $strParams) . _nl();
            }
        }
        return $strScript;
    }

    /**
     * Return the javascript command array, for use by form ajax response. Will erase the command array, so
     * the form better use it.
     * @static
     * @return array
     */
    public static function GetJavascriptCommandArray()
    {

        if (QApplication::$JavascriptExclusiveCommand) {
            // only render this one;
            $a[QAjaxResponse::CommandsMedium] = [QApplication::$JavascriptExclusiveCommand];
            QApplication::$JavascriptExclusiveCommand = null;
            return $a;
        }

        // Combine the javascripts into one array item
        $scripts = array();
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium])) {
            $scripts = QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium];
        }
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh])) {
            $scripts = array_merge(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh], $scripts);
            unset (QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsHigh]);
        }
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsLow])) {
            $scripts = array_merge($scripts, QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsLow]);
            unset (QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsLow]);
        }
        if (!empty(QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsFinal])) {
            $scripts = array_merge($scripts, QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsFinal]);
            unset (QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsFinal]);
        }
        if ($scripts) {
            QApplication::$JavascriptCommandArray[QAjaxResponse::CommandsMedium] = $scripts;
        }

        // add the file inclusion array onto the front of the command array
        $a = array_merge(QApplication::$JavascriptFileArray, QApplication::$JavascriptCommandArray);
        QApplication::$JavascriptFileArray = array();
        QApplication::$JavascriptCommandArray = array();
        return $a;
    }


    /**
     * Print an ajax response to the browser.
     *
     * @param array $strResponseArray An array keyed with QAjaxResponse items. These items will be read by the qcubed.js
     * ajax success function and operated on. The goals is to eventually have all possible response types represented
     * in the QAjaxResponse so that we can remove the "eval" in qcubed.js.
     */
    public static function SendAjaxResponse(array $strResponseArray)
    {
        header('Content-Type: text/json'); // not application/json, as IE reportedly blows up on that, but jQuery knows what to do.
        $strJSON = JavascriptHelper::toJSON($strResponseArray);
        if (QApplication::$EncodingType && QApplication::$EncodingType != 'UTF-8') {
            $strJSON = iconv(QApplication::$EncodingType, 'UTF-8', $strJSON); // json must be UTF-8 encoded
        }
        print ($strJSON);
    }


    /**
     * Utility function to get the JS file URI, given a string input
     * @param string $strFile File name to be tested
     *
     * @return string the final JS file URI
     */
    public static function GetJsFileUri($strFile)
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
    public static function GetCssFileUri($strFile)
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
    public static function VarDump()
    {
        _p('<div class="var-dump"><strong>QCubed Settings</strong><ul>', false);
        $arrValidationErrors = QInstallationValidator::Validate();
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
        printf('<li>DocumentRoot = "%s"</li>', Application::instance()->context()->docRoot());
        printf('<li>QApplication::$EncodingType = "%s"</li>', QApplication::$EncodingType);
        printf('<li>QApplication::$PathInfo = "%s"</li>', QApplication::$PathInfo);
        printf('<li>QueryString = "%s"</li>', Application::instance()->context()->queryString());
        printf('<li>RequestUri = "%s"</li>', Application::instance()->context()->requestUri());
        printf('<li>QApplication::$ScriptFilename = "%s"</li>', QApplication::$ScriptFilename);
        printf('<li>QApplication::$ScriptName = "%s"</li>', QApplication::$ScriptName);
        printf('<li>ServerAddress = "%s"</li>', Application::instance()->context()->serverAddress());

        if (QApplication::$Database) {
            foreach (QApplication::$Database as $intKey => $objObject) {
                printf('<li>QApplication::$Database[%s] settings:</li>', $intKey);
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

/**
 * Class for enumerating Javascript priority.
 * These are taken out of a parameter list, and so are very unlikely strings to include normally.
 */
class QJsPriority
{
    /** Standard Priority */
    const Standard = '*jsMed*';
    /** High prioriy JS */
    const High = '*jsHigh*';
    /** Low Priority JS */
    const Low = '*jsLow*';
    /** Execute ONLY this command and exclude all others */
    const Exclusive = '*jsExclusive*';
    /** Execute this command after all ajax commands have been completely flushed */
    const Last = '*jsFinal*';
}

