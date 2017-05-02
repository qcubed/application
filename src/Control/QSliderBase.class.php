<?php
	/**
	 * QSlider Base File
	 * 
	 * The  QSliderBase class defined here provides an interface between the generated
	 * QSliderGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QSlider.class.php file in
	 * the controls folder instead.
	 *
	 */


	/**
	 * 
	 * Implements a JQuery UI Slider
	 * 
	 * A slider can have one or two handles to represent a range of things, similar to a scroll bar.
	 * 
	 * Use the inherited properties to manipulate it. Call Value or Values to get the values.
	 * 
	 * @link http://jqueryui.com/slider/
	 * @package Controls\Base
	 *
	 */
	class QSliderBase extends QSliderGen	{

		/** Constants to use for setting Orientation */
		const Vertical = 'vertical';
		const Horizontal = 'horizontal';

		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qcubed.slider', $this->GetJqControlId(), QJsPriority::High);
			return $strJS;
		}

		/**
		 * Returns the state data to restore later.
		 * @return mixed
		 */
		protected function GetState() {
			if ($this->mixRange === true) {
				return ['values'=>$this->Values];
			}
			else {
				return ['value'=>$this->Value];
			}
		}

		/**
		 * Restore the state of the control.
		 * @param mixed $state
		 */
		protected function PutState($state) {
			if (isset($state['values'])) {
				$this->Values = $state['values'];
			}
			elseif (isset($state['value'])) {
				$this->Value = $state['value'];
			}
		}


		public function __set($strName, $mixValue) {

			switch ($strName) {
				case '_Value':	// Internal Only. Used by JS above. Do Not Call.
					try {
						$this->intValue = \QCubed\Type::Cast($mixValue, \QCubed\Type::INTEGER);
					} catch (\QCubed\Exception\Caller $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case '_Values': // Internal Only. Used by JS above. Do Not Call.
					try {
						$aValues = explode (',', $mixValue);
						$aValues[0] = \QCubed\Type::Cast( $aValues[0], \QCubed\Type::INTEGER); // important to make sure JS sends values as ints instead of strings
						$aValues[1] = \QCubed\Type::Cast($aValues[1], \QCubed\Type::INTEGER); // important to make sure JS sends values as ints instead of strings
						$this->arrValues = $aValues;
					} catch (\QCubed\Exception\Caller $objExc) {
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


	}