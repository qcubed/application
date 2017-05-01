<?php
/**
 * Displays an alert to the user
 *
 * @package Actions
 */
class QAlertAction extends QAction {
    /** @var string Message to be shown as the alert */
    protected $strMessage;

    /**
     * Constructor
     *
     * @param string $strMessage Message to be shown as the alert
     */
    public function __construct($strMessage) {
        $this->strMessage = $strMessage;
    }

    /**
     * PHP Magic function to get the property values of an object of the class
     *
     * @param string $strName Name of the property
     *
     * @return mixed|null|string
     * @throws QCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'Message':
                return $this->strMessage;
            default:
                try {
                    return parent::__get($strName);
                } catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string
     */
    public function RenderScript(QControl $objControl) {
        $strMessage = JavaScriptHelper::toJsObject($this->strMessage);

        return sprintf("alert(%s);", $strMessage);
    }
}
