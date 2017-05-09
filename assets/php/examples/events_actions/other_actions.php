<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $btnFocus;
		protected $btnSelect;
		protected $txtFocus;

		protected $btnToggleDisplay;
		protected $txtDisplay;

		protected $btnToggleEnable;
		protected $txtEnable;
		
		protected $pnlHover;
		
		protected $btnCssAction;

		protected function formCreate() {
			// Define the Textboxes
			$this->txtFocus = new \QCubed\Project\Control\TextBox($this);
			$this->txtFocus->Text = 'Example Text Here';
			$this->txtDisplay = new \QCubed\Project\Control\TextBox($this);
			$this->txtDisplay->Text = 'Example Text Here';
			$this->txtEnable = new \QCubed\Project\Control\TextBox($this);
			$this->txtEnable->Text = 'Example Text Here';

			// \QCubed\Action\FocusControl example
			$this->btnFocus = new \QCubed\Project\Jqui\Button($this);
			$this->btnFocus->Text = 'Set Focus';
			$this->btnFocus->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\FocusControl($this->txtFocus));

			// \QCubed\Action\SelectControl example
			$this->btnSelect = new \QCubed\Project\Jqui\Button($this);
			$this->btnSelect->Text = 'Select All in Textbox';
			$this->btnSelect->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\SelectControl($this->txtFocus));

			// \QCubed\Action\ToggleDisplay example
			$this->btnToggleDisplay = new \QCubed\Project\Jqui\Button($this);
			$this->btnToggleDisplay->Text = 'Toggle the Display (show/hide)';
			$this->btnToggleDisplay->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\ToggleDisplay($this->txtDisplay));

			// \QCubed\Action\ToggleEnable example
			$this->btnToggleEnable = new \QCubed\Project\Jqui\Button($this);
			$this->btnToggleEnable->Text = 'Toggle the Enable (enabled/disabled)';
			$this->btnToggleEnable->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\ToggleEnable($this->txtEnable));

			// \QCubed\Action\CssClass example
			$this->pnlHover = new \QCubed\Control\Panel($this);
			$this->pnlHover->HtmlEntities = false;
			$this->pnlHover->Text = 'Change the CSS class of a control using <b>\QCubed\Action\CssClass</b>:<br /><br />(Uses QMouseOver and QMouseOut to Temporarily Override the Panel\'s CSS Style)';

			// Set a Default Style
			$this->pnlHover->CssClass = 'panelHover';

			// Add QMouseOver and QMouseOut actions to set and then reset temporary style overrides
			// Setting the TemporaryCssClass to "null" will "reset" the style back to the default
			$this->pnlHover->AddAction(new \QCubed\Event\MouseOver(), new \QCubed\Action\CssClass('panelHighlight', true));
			$this->pnlHover->AddAction(new \QCubed\Event\MouseOut(), new \QCubed\Action\CssClass());
			
			$this->btnCssAction = new \QCubed\Project\Jqui\Button($this);
			$this->btnCssAction->Text = "click me to change my background color!";
			$this->btnCssAction->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\CssAction("background", "green"));
		}
	}

	ExampleForm::Run('ExampleForm');
?>
