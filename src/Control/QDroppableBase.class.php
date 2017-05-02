<?php
	/**
	 * The QDroppableBase class defined here provides an interface between the generated
	 * QDroppableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDroppable.class.php file instead.
	 *
	 */

	/**
	 * Implements the Droppable capabilities of JQuery UI in a QControl
	 * 
	 * This class is designed to work as a kind of add-on class to a QControl, giving its capabilities
	 * to the control. To make a QControl droppable, simply set $ctl->Droppable = true. You can then 
	 * get to this class to further manipulate the aspects of the droppable through $ctl->DropObj.
	 *
	 * @property String $DroppedId ControlId of a control that was dropped onto this
	 * 
	 * @link http://jqueryui.com/droppable/
	 * @package Controls\Base
	 */
	class QDroppableBase extends QDroppableGen
	{

		/** @var string */
		protected $strDroppedId = null;

		// redirect all js requests to the parent control
		public function getJqControlId() {
			return $this->objParentControl->ControlId;
		}
		
		public function Render($blnDisplayOutput = true) {}
		protected function GetControlHtml() {}
		public function Validate() {return true;}
		public function ParsePostData() {}

		// These functions are used to keep track of the selected value, and to implement 
		// optional autocomplete functionality.
		

		// These functions are used to keep track of the selected value, and to implement 
		// optional autocomplete functionality.
		
		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qcubed.droppable', $this->GetJqControlId(), $this->ControlId, QJsPriority::High);
			return $strJS;
		}

		/**
		 * PHP __set magic method implementation
		 * @param string $strName Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @throws \QCubed\Exception\Caller|\QCubed\Exception\InvalidCast
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_DroppedId': // Internal only. Do not use. Used by JS above to track user actions.
					try {
						$this->strDroppedId = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
					} catch (\QCubed\Exception\InvalidCast $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
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

		/**
		 * PHP __get magic method implementation
		 * @param string $strName Property Name
		 *
		 * @return mixed
		 * @throws \QCubed\Exception\Caller
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'DroppedId': return $this->strDroppedId;
				
				default: 
					try { 
						return parent::__get($strName); 
					} catch (\QCubed\Exception\Caller $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
		
	}