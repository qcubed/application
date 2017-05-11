<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblMessage;
	protected $btnButton;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define the Label
		$this->lblMessage = new \QCubed\Control\Label($this);
		$this->lblMessage->Text = 'Click the button to change my message.';

		// Definte the Button
		$this->btnButton = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton->Text = 'Click Me!';

		// Add a Click event handler to the button -- the action to run is an AjaxAction.
		// The AjaxAction names a PHP method (which will be run asynchronously) called "btnButton_Click"
		$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton_Click'));
	}

	// The "btnButton_Click" Event handler
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		$this->lblMessage->Text = 'Hello, world!';
	}

}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>