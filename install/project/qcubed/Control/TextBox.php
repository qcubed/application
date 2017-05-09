<?php

namespace QCubed\Project\Control;

/**
 * Class TextBox
 * @package QCubed\Project\Control
 * @was QTextBox
 */
class TextBox extends \QCubed\Control\TextBoxBase
{
    // Feel free to specify global display preferences/defaults for all TextBox controls
    /** @var string Default CSS class for the textbox */
    protected $strCssClass = 'textbox';

    public function __construct($objParentObject, $strControlId = null)
    {
        parent::__construct($objParentObject, $strControlId);

        /**
         * This is the default purifier, used to purify text coming from users to make sure it doesn't contain
         * malicious cross site scripts. If you install Html Purifier, it will use that one. Otherwise, it will do its
         * best with just putting html entities on what is coming through, which is not very good. You really should install
         * html purifier.
         */

        if (class_exists('HTMLPurifier')) {
            $this->strCrossScripting = self::XSS_HTML_PURIFIER;
        } else {
            $this->strCrossScripting = self::XSS_HTML_ENTITIES;
        }

    }
}
