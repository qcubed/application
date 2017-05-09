<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $btnButton;
	// The class member variable of the intCounter to show off a \QCubed\Project\Control\FormBase's state
	protected $intCounter = 0;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Definte the Button
		$this->btnButton = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton->Text = 'Click Me!';

		// Add a Click event handler to the button -- the action to run is a ServerAction (e.g. PHP method)
		// called "btnButton_Click"
		$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('btnButton_Click'));
	}

	// The "btnButton_Click" Event handler
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		// Increment our counter
		$this->intCounter++;
	}

}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>