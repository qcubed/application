<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Error;

class Manager
{
    /** @var bool */
    public static $errorFlag = false; // indicates an error occurred

    /**
     * Set Error/Exception Handling to the default
     * QCubed HandleError and HandlException functions
     * (Only in non CLI mode)
     *
     * Feel free to change, if needed, to your own
     * custom error handling script(s).
     */
    public static function initialize() {
        if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
            set_error_handler(['\\QCubed\\Error\\Manager', 'handleError'], error_reporting());
            set_exception_handler(['\\QCubed\\Error\\Manager', 'handleException']);
            register_shutdown_function(['\\QCubed\\Error\\Manager', 'shutdown']);
        }
    }

    /**
     * QCubed's default error handler. This is used by the Error\Handler class to do error management. You should
     * not normally need this.
     *
     * Note:  $__exc_errcontext has been deprecated as of PHP 7.2, so is no longer used.
     *
     * @param $errNum
     * @param $errStr
     * @param $errFile
     * @param $errLine
     * @return bool
     */
    public static function handleError($errNum, $errStr, $errFile, $errLine)
    {
        // If a command is called with "@", then we should return
        if (error_reporting() == 0) {
            return true;
        }

        if (!self::$errorFlag) {
            self::$errorFlag = true;
        } else {
            return true; // Already are handling an error. Indicates an additional error condition during error handling
        }

        switch ($errNum) {
            case E_ERROR:
                $code = "E_ERROR";
                break;
            case E_WARNING:
                $code = "E_WARNING";
                break;
            case E_PARSE:
                $code = "E_PARSE";
                break;
            case E_NOTICE:
                $code = "E_NOTICE";
                break;
            case E_STRICT:
                $code = "E_STRICT";
                break;
            case E_CORE_ERROR:
                $code = "E_CORE_ERROR";
                break;
            case E_CORE_WARNING:
                $code = "E_CORE_WARNING";
                break;
            case E_COMPILE_ERROR:
                $code = "E_COMPILE_ERROR";
                break;
            case E_COMPILE_WARNING:
                $code = "E_COMPILE_WARNING";
                break;
            case E_USER_ERROR:
                $code = "E_USER_ERROR";
                break;
            case E_USER_WARNING:
                $code = "E_USER_WARNING";
                break;
            case E_USER_NOTICE:
                $code = "E_USER_NOTICE";
                break;
            case E_DEPRECATED:
                $code = 'E_DEPRECATED';
                break;
            case E_USER_DEPRECATED:
                $code = 'E_USER_DEPRECATED';
                break;
            case E_RECOVERABLE_ERROR:
                $code = 'E_RECOVERABLE_ERROR';
                break;
            default:
                $code = "Unknown";
                break;
        }

        static::displayError(
            "Error",
            $errNum,
            $code,
            $errStr,
            $errFile,
            $errLine,
            self::getBacktrace(),
            null
        );
        return false;
    }

    /**
     * Returns a stringified version of a backtrace.
     * Set $blnShowArgs if you want to see a representation of the arguments. Note that if you are sending
     * in objects, this will unpack the entire structure and display its contents.
     * $intSkipTraces is how many back traces you want to skip. Set this to at least one to skip the
     * calling of this function itself.
     *
     * @param bool $blnShowArgs
     * @param int $intSkipTraces
     * @return string
     */
    public static function getBacktrace($blnShowArgs = false, $intSkipTraces = 1) {
        if (!$blnShowArgs) {
            $b = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        } else {
            $b = debug_backtrace(false);
        }
        $strRet = "";
        for ($i = $intSkipTraces; $i < count($b); $i++) {
            $item = $b[$i];

            $strFile = (array_key_exists("file", $item)) ? $item["file"] : "";
            $strLine = (array_key_exists("line", $item)) ? $item["line"] : "";
            $strClass = (array_key_exists("class", $item)) ? $item["class"] : "";
            $strType = (array_key_exists("type", $item)) ? $item["type"] : "";
            $strFunction = (array_key_exists("function", $item)) ? $item["function"] : "";

            $vals = [];
            if (!empty($item["args"])) {
                foreach ($item["args"] as $val) {
                    $vals[] = print_r($val, true);
                }
            }
            $strArgs = implode(", ", $vals);

            $strRet .= sprintf("#%s %s(%s): %s%s%s(%s)\n",
                $i,
                $strFile,
                $strLine,
                $strClass,
                $strType,
                $strFunction,
                $strArgs);
        }

        return $strRet;
    }

    public static function handleException($__exc_objException)
    {
        if (!self::$errorFlag) {
            self::$errorFlag = true;
        } else {
            return; // Already are handling an error. Indicates an additional error condition during error handling
        }

        global $__exc_strType;
        if (isset($__exc_strType)) {
            return;
        } // error was already called, avoid endless looping

        $__exc_objReflection = new \ReflectionObject($__exc_objException);

        $__exc_strType = "Exception";
        $__exc_errno = $__exc_objException->ErrorNumber;
        $__exc_strMessage = $__exc_objException->getMessage();
        $__exc_strObjectType = $__exc_objReflection->getName();

        $__exc_objErrorAttributeArray = [];

        if ($__exc_objException instanceof \QCubed\Database\Exception\Base) {
            $__exc_objErrorAttribute = new QErrorAttribute("Database Error Number", $__exc_errno, false);
            $__exc_objErrorAttributeArray[0] = $__exc_objErrorAttribute;

            if ($__exc_objException->Query) {
                $__exc_objErrorAttribute = new QErrorAttribute("Query", $__exc_objException->Query, true);
                $__exc_objErrorAttributeArray[1] = $__exc_objErrorAttribute;
            }
        }

        if ($__exc_objException instanceof \QCubed\Exception\DataBind) {
            if ($__exc_objException->Query) {
                $__exc_objErrorAttribute = new QErrorAttribute("Query", $__exc_objException->Query, true);
                $__exc_objErrorAttributeArray[1] = $__exc_objErrorAttribute;
            }
        }

        $__exc_strFilename = $__exc_objException->getFile();
        $__exc_intLineNumber = $__exc_objException->getLine();
        $__exc_strStackTrace = trim($__exc_objException->getTraceAsString());

        self::displayError($__exc_strType,
            $__exc_errno,
            $__exc_strObjectType,
            $__exc_strMessage,
            $__exc_strFilename,
            $__exc_intLineNumber,
            $__exc_strStackTrace,
            $__exc_objErrorAttributeArray);
    }

    protected static function displayError(
        $__exc_strType,
        $__exc_errno,
        $__exc_strObjectType,
        $__exc_strMessage,
        $__exc_strFilename,
        $__exc_intLineNumber,
        $__exc_strStackTrace,
        $__exc_objErrorAttributeArray

    ) {
        if (ob_get_length()) {
            $__exc_strRenderedPage = ob_get_contents();
            ob_clean();
        }
        if (defined('ERROR_PAGE_PATH')) {
            require(ERROR_PAGE_PATH);
        } else {
            // Error in installer or similar - ERROR_PAGE_PATH constant is not defined yet.
            echo "error: errno: " . $__exc_errno . "<br/>" . $__exc_strMessage . "<br/>" . $__exc_strFilename . ":" . $__exc_intLineNumber;
        }
        exit();
    }

    /**
     * Some errors are not caught by a php custom error handler, which can cause the system to silently fail.
     * This shutdown function will catch those errors.
     */

    public static function shutdown()
    {
        if (defined('__TIMER_OUT_FILE__') && class_exists('\\QCubed\\Timer')) {
            $strTimerOutput = \QCubed\Timer::VarDump(false);
            if ($strTimerOutput) {
                file_put_contents(__TIMER_OUT_FILE__, $strTimerOutput . "\n", FILE_APPEND);
            }
        }

        $error = error_get_last();
        if ($error &&
            is_array($error)
            /*&&
            (!defined('QCodeGen::DebugMode') || QCodeGen::DebugMode)*/
        ) { // if we are codegenning, only error if we are in debug mode. Prevents chmod error.

            self::handleError (
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            );
        }
    }

}