<?php

namespace QCubed\Project\Control;

use QCubed as Q;

/**
 * Button class - You may modify it to contain your own modifications to the
 * Button throughout the framework.
 * @package Controls
 * @was QRadioButton
 */
class RadioButton extends \QCubed\Control\RadioButtonBase
{
    ///////////////////////////
    // Button Preferences
    ///////////////////////////

    // Feel free to specify global display preferences/defaults for all QButton controls
    protected $strCssClass = 'radio';

    /**
     * Returns the generator corresponding to this control.
     *
     * @return Q\Generator\GeneratorBase
     */
    public static function getCodeGenerator() {
        return new Q\Generator\TextBox(__CLASS__);
    }

}
