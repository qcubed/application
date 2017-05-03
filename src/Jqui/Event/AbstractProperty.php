<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Jqui\Event;

/**
 * Class AbstractProperty
 *
 * When properties of a jQuery-UI widget change
 * Currently, Date-Time related jQuery-UI controls are derived from this one
 *
 * @property-read string $JqProperty The property string
 * @package QCubed\Jqui\Event
 * @was QJqUiPropertyEvent
 */
abstract class AbstractProperty extends \QCubed\Event\AbstractBase
{
    /** @var string The property JS string */
    protected $strJqProperty = '';

    /**
     * PHP Magic method to get properties from this class
     * @param string $strName
     *
     * @return mixed
     * @throws Exception|\QCubed\Exception\Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case 'JqProperty':
                return $this->strJqProperty;
            default:
                try {
                    return parent::__get($strName);
                } catch (\QCubed\Exception\Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }
}
