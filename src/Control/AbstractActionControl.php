<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Control;

use QCubed\Project\Control\ControlBase as QControl;

/**
 * Class AbstractActionControl
 *
 * Abstract class which is extended by things like Buttons.
 * It basically pre-sets CausesValidation to be true (b/c most of the time,
 * when a button is clicked we'd assume that we want the validation to kick off)
 * And it pre-defines ParsePostData and Validate.
 *
 * @was QActionControl
 * @package QCubed\Control
 */
abstract class AbstractActionControl extends QControl
{
    /**
     * This function should contain the POST data parsing mechanism
     */
    public function parsePostData()
    {
    }

    /**
     * Checks whether the value submitted via POST for the control was valid or not
     * The code to test the validity will have to reside in this function
     * @return bool Whether or not the validation succeeded
     */
    public function validate()
    {
        return true;
    }
}