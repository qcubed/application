<?php
    require_once('../qcubed.inc.php');

    class ExampleForm extends \QCubed\Project\Control\FormBase {
        protected $objPanel1;
        protected $objPanel2;
        protected $objPanel3;
        protected $objPanel4;

        protected function formCreate() {
            /*
            * These two panels here will demonstrate even bubbling
            */
            $this->objPanel1 = new \QCubed\Control\Panel($this);
            $this->objPanel1->AutoRenderChildren = true;
            $this->objPanel1->CssClass = 'container';
            $this->objPanel1->Text = "I'm panel 1";
            $this->objPanel1->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('objPanel1_Click'));

            $this->objPanel2 = new \QCubed\Control\Panel($this->objPanel1);
            $this->objPanel2->CssClass = 'container';
            $this->objPanel2->AddCssClass('insidePanel');
            $this->objPanel2->Text = "I'm panel 2 and I'm a child of panel 1.<br/><br/>If you click me, both my click action and panel 1's click action will fire";
            $this->objPanel2->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('objPanel2_Click'));

            /*
            * These two panels here will demenstrate how to STOP even bubbling
            */
            $this->objPanel3 = new \QCubed\Control\Panel($this);
            $this->objPanel3->CssClass = 'container';
            $this->objPanel3->AutoRenderChildren = true;
            $this->objPanel3->Text = "I'm panel 3";
            $this->objPanel3->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('objPanel3_Click'));

            $this->objPanel4 = new \QCubed\Control\Panel($this->objPanel3);
            $this->objPanel4->CssClass = 'container';
            $this->objPanel4->AddCssClass('insidePanel');
            $this->objPanel4->Text = "I'm panel 4 and I'm a child of panel 3.<br/><br/>If you click me only my click action will fire thanks to \QCubed\Action\StopPropagation";
            // Note the addition of \QCubed\Action\StopPropagation()
            $this->objPanel4->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\StopPropagation());
            $this->objPanel4->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('objPanel4_Click'));

        }

        public function objPanel1_Click($strFormId, $strControlId, $strParameter) {
            \QCubed\Project\Application::DisplayAlert('Panel 1 Clicked');
        }

        public function objPanel2_Click($strFormId, $strControlId, $strParameter) {
            \QCubed\Project\Application::DisplayAlert('Panel 2 Clicked');
        }

        public function objPanel3_Click($strFormId, $strControlId, $strParameter) {
            \QCubed\Project\Application::DisplayAlert('Panel 3 Clicked');
        }

        public function objPanel4_Click($strFormId, $strControlId, $strParameter) {
            \QCubed\Project\Application::DisplayAlert('Panel 4 Clicked, panel 3 will not trigger a click');
        }

    }

    ExampleForm::Run('ExampleForm');
?>