<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $ctlCustom;

		protected function formCreate() {
			// Get the Custom Control
			$this->ctlCustom = new QSampleControl($this);

			// Note that custom controls can act just like regular controls,
			// complete with events and attributes
			$this->ctlCustom->Foo = 'Click on me!';
			$this->ctlCustom->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Alert('Hello, world!'));
		}
	}

	// And now run our defined form
	ExampleForm::Run('ExampleForm');
?>
