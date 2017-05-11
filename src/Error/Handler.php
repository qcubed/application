<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Error;

/**
 * Class Handler
 *
 * An object you can create locally that will temporarily change the error handler to the given function.
 *
 * @package QCubed\Error
 */
class Handler
{
    protected $intStoredErrorLevel;

    /**
     * Handler constructor.
     *
     * @param callable $func    A callable that will be used temporarily as the function
     * @param null $intLevel
     */
    public function __construct(callable $func, $intLevel = null)
    {
        if (!$func) {
            // No Error Handling is wanted -- simulate a "On Error, Resume" type of functionality
            //set_error_handler(['\\QCubed\\ErrorHandler::QcubedHandleError'], 0);
            set_error_handler(['\\QCubed\\Error\\Manager::QCubedHandleError'], 0);
            $this->intStoredErrorLevel = error_reporting(0);
        } else {
            set_error_handler($func, $intLevel);
            $this->intStoredErrorLevel = -1;
        }
    }

    /**
     * Restores the temporarily overridden default error handling mechanism back to the default.
     */
    public function restore()
    {
        if ($this->intStoredErrorLevel !== null) {
            if ($this->intStoredErrorLevel != -1) {
                error_reporting($this->intStoredErrorLevel);
            }
            restore_error_handler();
            $this->intStoredErrorLevel = null;
        }
    }

    /**
     * Makes sure the error handler gets restored.
     */
    public function __destruct()
    {
        $this->restoreErrorHandler();
    }
}