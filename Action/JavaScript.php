<?php
/**
 * Client-side action - no postbacks of any kind are performed.
 * All handling activity happens in Javascript.
 *
 * @package Actions
 */
class QJavaScriptAction extends QAction {
    /** @var string JS to be run on the client side */
    protected $strJavaScript;

    /**
     * The constructor
     * @param string $strJavaScript JS which is to be executed on the client side
     */
    public function __construct($strJavaScript) {
        $this->strJavaScript = trim($strJavaScript);
        if (QString::LastCharacter($this->strJavaScript) == ';') {
            $this->strJavaScript = substr($this->strJavaScript, 0, strlen($this->strJavaScript) - 1);
        }
    }

    /**
     * PHP Magic function to get the property values of a class object
     *
     * @param string $strName Name of the property
     *
     * @return mixed|null|string
     * @throws QCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'JavaScript':
                return $this->strJavaScript;
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
     * Returns the JS which will be executed on the client side
     * @param QControl $objControl
     *
     * @return string
     */
    public function RenderScript(QControl $objControl) {
        return sprintf('%s;', $this->strJavaScript);
    }
}
