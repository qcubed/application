<?php
use QCubed\Action\Server;
use QCubed\Control\Label;
use QCubed\Event\Click;
use QCubed\Project\Control\Button;
use QCubed\Project\Control\FormBase;
use QCubed\Project\Control\ListBox;
use QCubed\Project\Control\TextBox;

require_once('../qcubed.inc.php');

class CalculatorForm extends FormBase {

	// Our Calculator needs 2 Textboxes (one for each operand)
	// A listbox of operations to choose from
	// A button to execute the calculation
	// And a label to output the result
	protected $txtValue1;
	protected $txtValue2;
	protected $lstOperation;
	protected $btnCalculate;
	protected $lblResult;

	// Define all the QContrtol objects for our Calculator
	protected function formCreate() {
		$this->txtValue1 = new TextBox($this);

		$this->txtValue2 = new TextBox($this);

		$this->lstOperation = new ListBox($this);
		$this->lstOperation->addItem('+', 'add');
		$this->lstOperation->addItem('-', 'subtract');
		$this->lstOperation->addItem('*', 'multiply');
		$this->lstOperation->addItem('/', 'divide');

		$this->btnCalculate = new Button($this);
		$this->btnCalculate->Text = 'Calculate';
		$this->btnCalculate->addAction(new Click(), new Server('btnCalculate_Click'));

		$this->lblResult = new Label($this);
		$this->lblResult->HtmlEntities = false;
	}

	// Perform the necessary operations on the operands, and output the value to the lblResult
	protected function btnCalculate_Click($strFormId, $strControlId, $strParameter) {
		switch ($this->lstOperation->SelectedValue) {
			case 'add':
				$mixResult = $this->txtValue1->Text + $this->txtValue2->Text;
				break;
			case 'subtract':
				$mixResult = $this->txtValue1->Text - $this->txtValue2->Text;
				break;
			case 'multiply':
				$mixResult = $this->txtValue1->Text * $this->txtValue2->Text;
				break;
			case 'divide':
				$mixResult = $this->txtValue1->Text / $this->txtValue2->Text;
				break;
			default:
				throw new Exception('Invalid Action');
		}

		$this->lblResult->Text = '<b>Your Result:</b> ' . $mixResult;
	}
}

// And now run our defined form
CalculatorForm::run('CalculatorForm');
