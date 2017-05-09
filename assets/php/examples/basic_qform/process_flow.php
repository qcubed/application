<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblMessage;
	protected $btnButton;

	protected function formRun() {
		_p('<br><br><br><br><br>', false); // Compensating for the examples header

		_p('<b>formRun</b> called<br/>', false);
	}

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		_p('<b>formCreate</b> called<br/>', false);
		// Define the Label -- Set HtmlEntities to false because we intend on hard coding HTML into the Control
		$this->lblMessage = new \QCubed\Control\Label($this);
		$this->lblMessage->HtmlEntities = false;
		$this->lblMessage->Text = 'Click the button to change my message.';

		// Definte the Button
		$this->btnButton = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton->Text = 'Click Me!';

		// We add CausesValidation to the Button so that formValidate() will get called
		$this->btnButton->CausesValidation = true;

		// Add a Click event handler to the button -- the action to run is a ServerAction (e.g. PHP method)
		// called "btnButton_Click"
		$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('btnButton_Click'));
	}

	protected function formLoad() {
		_p('<b>formLoad</b> called<br/>', false);
	}

	protected function formPreRender() {
		_p('<b>formPreRender</b> called<br/>', false);
	}

	protected function formValidate() {
		_p('<b>formValidate</b> called<br/>', false);

		// Form_Validate needs to return true or false
		return true;
	}

	protected function formExit() {
		_p('<b>formExit</b> called<br/>', false);
	}

	// The "btnButton_Click" Event handler
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		_p('<b>btnButton_Click</b> called<br/>', false);
		$this->lblMessage->Text = 'Hello, world!<br/>';
		$this->lblMessage->Text .= 'Note that instead of <b>formCreate</b> being called, we are now calling <b>formLoad</b> and <b>btnButton_Click</b>';
	}

}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>