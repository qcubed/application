<?php
/**
 * Client-side action - no postbacks of any kind are performed.
 * All handling activity happens in Javascript.
 *
 * @package Actions
 */
class QRedirectAction extends QAction {
    /** @var string JS to be run on the client side */
    protected $strJavaScript;

    /**
     * possible values:
     * http://google.com
     * index.php?page=view
     * /foo/bar/woot.html
     *
     * @param string $strUrl
     */
    public function __construct($strUrl) {
        $this->strJavaScript = sprintf("document.location.href ='%s'", trim($strUrl));
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
     * Returns the JS which runs on the client side
     * @param QControl $objControl
     *
     * @return string
     */
    public function RenderScript(QControl $objControl) {
        return sprintf('%s;', $this->strJavaScript);
    }
}
