<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends \QCubed\Project\Control\FormBase {
		// Declare the Proxy Control
		// Notice how this control is NEVER RENDERED outright.  Instead, we use
		// RenderAsHref() and RenderAsEvents() on it.
		protected $pxyExample;
		protected $pnlHover;

		// For this example, show how to use custom HTML to trigger events that updates this Message label
		protected $lblMessage;

		protected function formCreate() {
			// Define the Proxy
			$this->pxyExample = new \QCubed\Control\Proxy($this);

			// Define a Message label
			$this->lblMessage = new \QCubed\Control\Label($this);

			// Define a Panel to display/hide whenever we're hovering
			$this->pnlHover = new \QCubed\Control\Panel($this);
			$this->pnlHover->Text = 'Hovering over a button or link...';
			$this->pnlHover->Padding = 10;
			$this->pnlHover->BorderStyle = \QCubed\Css\BorderStyle::SOLID;
			$this->pnlHover->BorderWidth = 1;
			$this->pnlHover->Width = 200;
			$this->pnlHover->BackColor = '#ffffcc';
			$this->pnlHover->Display = false;

			// Define any applicable actions on the Proxy
			// Note that all events will flow through to any DOM element (in the HTML) that is calling RenderAsEvents.
			$this->pxyExample->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('pxyExample_Click'));
			$this->pxyExample->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Terminate());
			$this->pxyExample->AddAction(new \QCubed\Event\MouseOver(), new \QCubed\Action\ToggleDisplay($this->pnlHover, true));
			$this->pxyExample->AddAction(new \QCubed\Event\MouseOut(), new \QCubed\Action\ToggleDisplay($this->pnlHover, false));
		}

		// Notice how the optional "action parameter" we used in the RenderAsHref() or RenderEvents() call gets passed in as $strParameter here.
		protected function pxyExample_Click($strFormId, $strControlId, $strParameter) {
			$this->lblMessage->Text = 'You clicked on: ' . $strParameter;
		}
	}

	ExampleForm::Run('ExampleForm');
?>
