<?php
	/**
	 * This file contains the QWaitIcon class.
	 *
	 * @package Controls
	 * @filesource
	 */

	/**
	 * @package Controls
	 *
	 * @property string $Text
	 * @property string $TagName
	 * @property string $Padding
	 * @property string $HorizontalAlign
	 * @property string $VerticalAlign
	 */
	class QWaitIcon extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var null|string String to be displayed (e.g. "Please wait") (can be HTML) */
		protected $strText = null;
		/** @var null|string Padding for the rendered element */
		protected $strPadding = null;
		/** @var string HTML tag name to be used for rendering the text */
		protected $strTagName = 'span';
		/** @var bool  */
		protected $blnDisplay = false;


		// LAYOUT
		/** @var string Horizontal alignment for the text of the wait icon */
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		/** @var string Vertical alignment for the wait icon */
		protected $strVerticalAlign = QVerticalAlign::NotSet;

		/**
		 * Constructor
		 *
		 * @param QControl|QControlBase|QForm $objParentObject Parent control/form of this wait icon
		 * @param null|string                 $strControlId    Control ID to be set for the wait icon
		 *
		 * @throws Exception|\QCubed\Exception\Caller
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strText = sprintf('<img src="%s/spinner_14.gif" width="14" height="14" alt="Please Wait..."/>', __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__);
		}

		/**
		 * Returns the styles attributes for the wait icon
		 *
		 * @return string CSS style attributes as one string
		 */
		public function GetStyleAttributes() {
			$strStyle = parent::GetStyleAttributes();

			if ($this->strPadding)
				$strStyle .= sprintf('padding:%s;', $this->strPadding);

			if (($this->strHorizontalAlign) && ($this->strHorizontalAlign != QHorizontalAlign::NotSet))
				$strStyle .= sprintf('text-align:%s;', $this->strHorizontalAlign);

			if (($this->strVerticalAlign) && ($this->strVerticalAlign != QVerticalAlign::NotSet))
				$strStyle .= sprintf('vertical-align:%s;', $this->strVerticalAlign);

			return $strStyle;
		}

		//////////
		// Methods
		//////////
		public function ParsePostData() {}

		/**
		 * Validates the wait icon (for now it just returns true)
		 *
		 * @return bool
		 */
		public function Validate() {return true;}

		/**
		 * Returns the HTML we have to send to the browser to render this wait icon
		 * @return string HTML to be returned
		 */
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();

			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			$strToReturn = sprintf('<%s id="%s" %s%s>%s</%s>',
				$this->strTagName,
				$this->strControlId,
				$this->GetAttributes(),
				$strStyle,
				$this->strText,
				$this->strTagName);

			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName Property name
		 *
		 * @return mixed|null|string
		 * @throws Exception|\QCubed\Exception\Caller
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "TagName": return $this->strTagName;
				case "Padding": return $this->strPadding;

				// LAYOUT
				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;

				default:
					try {
						return parent::__get($strName);
					} catch (\QCubed\Exception\Caller $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName  Property name
		 * @param string $mixValue Property value
		 *
		 * @return mixed|void
		 * @throws Exception|\QCubed\Exception\Caller|\QCubed\Exception\InvalidCast
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "Text":
					try {
						$this->strText = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
						break;
					} catch (\QCubed\Exception\InvalidCast $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "TagName":
					try {
						$this->strTagName = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
						break;
					} catch (\QCubed\Exception\InvalidCast $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Padding":
					try {
						$this->strPadding = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
						break;
					} catch (\QCubed\Exception\InvalidCast $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HorizontalAlign":
					try {
						$this->strHorizontalAlign = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
						break;
					} catch (\QCubed\Exception\InvalidCast $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "VerticalAlign":
					try {
						$this->strVerticalAlign = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
						break;
					} catch (\QCubed\Exception\InvalidCast $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (\QCubed\Exception\Caller $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}