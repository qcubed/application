<?php
    require_once('../qcubed.inc.php');
    
	class SelectForm extends \QCubed\Project\Control\FormBase {
		protected $list1;

		protected $btnServer;
		protected $btnAjax;

		protected $a;


		protected function formCreate() {
			$this->a = [new \QCubed\Control\HListItem ('A', 1),
				new \QCubed\Control\HListItem ('B', 2),
				new \QCubed\Control\HListItem ('C', 3),
				new \QCubed\Control\HListItem ('D', 4)
			];

			$this->list1 = new \QCubed\Control\HList($this);
			$this->list1->Name = 'List';


			$this->list1->AddItems($this->a);
			$this->list1->SetDataBinder([$this, 'DataBind']);

			$this->btnServer = new \QCubed\Project\Control\Button ($this);
			$this->btnServer->Text = 'Server Submit';
			$this->btnServer->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('submit_click'));

			$this->btnAjax = new \QCubed\Project\Control\Button ($this);
			$this->btnAjax->Text = 'Ajax Submit';
			$this->btnAjax->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('submit_click'));
		}

		protected function submit_click($strFormId, $strControlId, $strParameter) {
		}

		public function DataBind() {

			$this->a[0]->AddItems(['aa'=>0, 'ab'=>2, 'ac'=>3]);
			$this->a[1]->AddItems(['ba'=>0, 'bb'=>1]);

			$this->list1->RemoveAllItems();
			$this->list1->AddListItems($this->a);
		}
		
	}
	SelectForm::Run('SelectForm');
?>