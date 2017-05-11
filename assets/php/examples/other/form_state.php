<?php
require_once('../qcubed.inc.php');

// First of all, let's override the way \QCubed\Project\Control\FormBase stores state information.
// We will use the session-based FormState Handler, instead of the standard/default
// formstate handler.  Also, let's encrypt the formstate index by defining
// an encryption key.
//
	// NOTE: This preference can be set, globally, by updating the \QCubed\Project\Control\FormBase class
// which is located at /includes/\QCubed\Project\Control\FormBase/\QCubed\Project\Control\FormBase.inc
\QCubed\Project\Control\FormBase::$FormStateHandler = '\QCubed\FormState\SessionHandler';
\QCubed\Project\Control\FormBase::$EncryptionKey = '\rSome.Random!Key\0';

// Everything else below should be the exact same as our original Hello, World! example
class ExampleForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblMessage;
	protected $btnButton;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define the Label
		$this->lblMessage = new \QCubed\Control\Label($this);
		$this->lblMessage->Text = 'Click the button to change my message.';

		// Define the Button
		$this->btnButton = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton->Text = 'Click Me!';

		// Add a Click event handler to the button
		$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('btnButton_Click'));
	}

	// The "btnButton_Click" Event handler
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		$this->lblMessage->Text = 'Hello, world!';
	}
}

// Run the Form we have defined
ExampleForm::Run('ExampleForm');
?>