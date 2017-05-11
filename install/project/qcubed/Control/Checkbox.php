<?php

namespace QCubed\Project\Control;

use QCubed as Q;

/**
 * Button class - You may modify it to contain your own modifications to the
 * Button throughout the framework.
 * @was QCheckbox
 */
class Checkbox extends \QCubed\Control\CheckboxBase
{
    ///////////////////////////
    // Button Preferences
    ///////////////////////////

    // Feel free to specify global display preferences/defaults for all QButton controls
    protected $strCssClass = 'checkbox';

    /**
     * Returns the generator corresponding to this control.
     *
     * @return Q\Generator\GeneratorBase
     */
    public static function getCodeGenerator() {
        return new Q\Generator\Checkbox(__CLASS__);
    }

}
