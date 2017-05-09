<?php
require_once('../qcubed.inc.php');

class CalculatorForm extends \QCubed\Project\Control\FormBase {

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
		$this->txtValue1 = new \QCubed\Project\Control\TextBox($this);

		$this->txtValue2 = new \QCubed\Project\Control\TextBox($this);

		$this->lstOperation = new \QCubed\Project\Control\ListBox($this);
		$this->lstOperation->AddItem('+', 'add');
		$this->lstOperation->AddItem('-', 'subtract');
		$this->lstOperation->AddItem('*', 'multiply');
		$this->lstOperation->AddItem('/', 'divide');

		$this->btnCalculate = new \QCubed\Project\Jqui\Button($this);
		$this->btnCalculate->Text = 'Calculate';
		$this->btnCalculate->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('btnCalculate_Click'));

		$this->lblResult = new \QCubed\Control\Label($this);
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
CalculatorForm::Run('CalculatorForm');
?>