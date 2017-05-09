<?php
require_once('../qcubed.inc.php');

class ExampleForm extends \QCubed\Project\Control\FormBase {
	protected $txtTextbox;

	protected $btnToggle;
	protected $btnHide;
	protected $btnShow;
	protected $btnBounce;
	protected $btnHighlight;
	protected $btnShake;
	protected $btnPulsate;
	protected $btnSize;
	protected $btnTransfer;

	protected function formCreate() {
		$this->txtTextbox = new \QCubed\Project\Control\TextBox($this);
		$this->txtTextbox->TextMode = \QCubed\Control\TextBoxBase::MULTI_LINE;
		$this->txtTextbox->Text = 'Click a button to start an animation.';
		$this->txtTextbox->Height = 200;

		$this->btnToggle = new \QCubed\Project\Jqui\Button($this);
		$this->btnToggle->Text = "toggle";

		$this->btnShow = new \QCubed\Project\Jqui\Button($this);
		$this->btnShow->Text = "show";

		$this->btnHide = new \QCubed\Project\Jqui\Button($this);
		$this->btnHide->Text = "hide";

		$this->btnBounce = new \QCubed\Project\Jqui\Button($this);
		$this->btnBounce->Text = "bounce";

		$this->btnHighlight = new \QCubed\Project\Jqui\Button($this);
		$this->btnHighlight->Text = "highlight";

		$this->btnShake = new \QCubed\Project\Jqui\Button($this);
		$this->btnShake->Text = "shake";

		$this->btnPulsate = new \QCubed\Project\Jqui\Button($this);
		$this->btnPulsate->Text = "pulsate";

		$this->btnSize = new \QCubed\Project\Jqui\Button($this);
		$this->btnSize->Text = "resize";

		$this->btnTransfer = new \QCubed\Project\Jqui\Button($this);
		$this->btnTransfer->Text = "transfer and hide";

		$this->btnToggle->AddAction     (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\ToggleEffect($this->txtTextbox, "scale", ""));
		$this->btnHide->AddAction       (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\HideEffect($this->txtTextbox, "blind"));
		$this->btnShow->AddAction       (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\ShowEffect($this->txtTextbox, "slide", "direction: 'up'"));
		$this->btnBounce->AddAction     (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Bounce($this->txtTextbox, "", 300));
		$this->btnHighlight->AddAction  (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Highlight($this->txtTextbox, "", 2000));
		$this->btnShake->AddAction      (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Shake($this->txtTextbox,"",300));
		$this->btnPulsate->AddAction    (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Pulsate($this->txtTextbox,"times:2",700));
		$this->btnSize->AddAction       (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Size($this->txtTextbox,"to: {width: 100, height: 100}, scale: 'box'"));

		// 3 events, one after the other, for the Shake action.
		$this->btnTransfer->AddAction   (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Show($this->txtTextbox, "fast"));
		$this->btnTransfer->AddAction   (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Transfer($this->txtTextbox, $this->btnTransfer));
		$this->btnTransfer->AddAction   (new \QCubed\Event\Click(), new \QCubed\Jqui\Action\Hide($this->txtTextbox, "fast"));
	}
}

ExampleForm::Run('ExampleForm');
?>