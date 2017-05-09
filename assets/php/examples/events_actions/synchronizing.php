<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends \QCubed\Project\Control\FormBase
	{
		/** @var  \QCubed\Project\Jqui\Button */
		protected $btnRegular;
		/** @var  \QCubed\Project\Jqui\Button */
		protected $btnBlocking;
		protected $intRegularCount = 0;
		protected $intBlockingCount = 0;


		protected $lblRegular;
		protected $lblBlocking;

		protected function formCreate()
		{
			$this->btnRegular = new \QCubed\Project\Jqui\Button($this);
			$this->btnRegular->Text = "Regular Button";
			$this->btnRegular->AddAction (new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnRegular_Click'));
			$this->btnBlocking = new \QCubed\Project\Jqui\Button($this);
			$this->btnBlocking->Text = "Blocking Button";
			$this->btnBlocking->AddAction (new \QCubed\Event\Click(0, null, null, true), new \QCubed\Action\Ajax('btnBlocking_Click'));

			// Define a Message label
			$this->lblRegular = new \QCubed\Control\Label($this);
			$this->lblRegular->Text = '0';
			$this->lblBlocking = new \QCubed\Control\Label($this);
			$this->lblBlocking->Text = '0';
		}

		protected function btnRegular_Click() {
			$this->intRegularCount += 1;
			$this->lblRegular->Text = $this->intRegularCount;
			$this->btnRegular->Enabled = false;
		}

		protected function btnBlocking_Click() {
			$this->intBlockingCount += 1;
			$this->lblBlocking->Text = $this->intBlockingCount;
			$this->btnBlocking->Enabled = false;
		}
	}

ExampleForm::Run('ExampleForm');

