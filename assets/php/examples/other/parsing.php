<?php

require_once('../qcubed.inc.php');
require('bb_parser.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExampleForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblResultRaw;
	protected $lblResultFormatted;
	protected $txtInput;
	protected $btnButton;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define the text area - multi-line \QCubed\Project\Control\TextBox
		$this->txtInput = new \QCubed\Project\Control\TextBox($this);
		$this->txtInput->TextMode = \QCubed\Control\TextBoxBase::MULTI_LINE;
		$this->txtInput->Text = "Hello\n\nworld. [b]We[/b] all " .
				"love [img]http://static.php.net/www.php.net/images/logos/php-med-trans-light.gif[/img]" .
				"\n\nThis is a [url=http://www.google.com]link to Google[/url].";

		// Define the Label
		$this->lblResultRaw = new \QCubed\Control\Label($this);
		$this->lblResultRaw->Text = 'Click the button to process the input.';

		$this->lblResultFormatted = new \QCubed\Control\Label($this);
		$this->lblResultFormatted->HtmlEntities = false;
		$this->lblResultFormatted->Text = 'Click the button to process the input.';

		// Define the Button
		$this->btnButton = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton->Text = 'Click Me!';

		$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton_Click'));
	}

	// In this click handler, we will process the BBCode in the input,
	// and format it properly to turn into HTML in the lblResult.
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		$strText = $this->txtInput->Text;

		// Parse the text, this is the class that knows how to act
		// on our rules
		$objParser = new BBCodeParser($strText);

		$result = $objParser->Render();
		$this->lblResultRaw->Text = $result;
		$this->lblResultFormatted->Text = $result;
	}

}

ExampleForm::Run('ExampleForm');
?>