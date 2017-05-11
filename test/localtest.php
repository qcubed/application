<?php

include ('../qcubed.inc.php');

include ('./unit/QTestControl.php');

// Run these tests within the context of a form
class QTestForm extends \QCubed\Project\Control\FormBase
{
    public $ctlTest;
    public $btnRunTests;
    public $lblRunning;
    public $pnlOutput;

    protected function formCreate() {
        $this->ctlTest = new QTestControl($this);
        $this->pnlOutput = new \QCubed\Control\Panel($this, 'outputPanel');
        $this->btnRunTests = new \QCubed\Project\Control\Button($this);
        $this->btnRunTests->Text = "Run Tests";
        $this->btnRunTests->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('startTesting'));

        $this->lblRunning = new \QCubed\Control\Label($this);
        $this->lblRunning->Text = "Running, please wait...";
        $this->lblRunning->Visible = false;
    }

    protected function startTesting() {
        $this->lblRunning->Visible = true;

        $t1 = new \QCubed\Project\Control\JsTimer($this, 50, false, true, 'timer1');
        $t1->AddAction(new \QCubed\Event\TimerExpired(), new \QCubed\Action\Ajax ('preTest'));
        $t2 = new \QCubed\Project\Control\JsTimer($this, 51, false, true, 'timer2');
        $t2->AddAction(new \QCubed\Event\TimerExpired(0,null,null,true), new \QCubed\Action\Ajax ('preTest2'));
        $t3 = new \QCubed\Project\Control\JsTimer($this, 52, false, true, 'timer3');
        $t3->AddAction(new \QCubed\Event\TimerExpired(), new \QCubed\Action\Ajax ('preTest3'));
        $t4 = new \QCubed\Project\Control\JsTimer($this, 600, false, true, 'timer4');
        $t4->AddAction(new \QCubed\Event\TimerExpired(), new \QCubed\Action\Server ('runTests'));
    }

    public function preTest() {
        $this->ctlTest->savedValue1 = 2;	// for test in QControlBaseTests
    }

    public function preTest2() {
        $this->longOperation(); // delay a bit
        $this->ctlTest->savedValue2 = $this->ctlTest->savedValue1;	// for test in QControlBaseTests
    }

    public function preTest3() {
        $this->ctlTest->savedValue3 = 1;	// This should NOT happen, since previous event should block it.
    }

    public function runTests() {
        $cliOptions = [ 'phpunit'];	// first entry is the command
        array_push($cliOptions, '-c', __DIR__ . '/phpunit-local.xml');	// the config file is here

        //require dirname(dirname(dirname(dirname(__FILE__)))) . '/autoload.php'; // Find PHPUnit_TextUI_Command

        $tester = new PHPUnit_TextUI_Command();

        $tester->run($cliOptions);

    }

    private function longOperation() {
        $a = [];
        for($i = 0; $i < 100000; $i++) {
            $a[] = rand(1,5000);
        }
        ksort($a);
    }

}

QTestForm::run('QTestForm', __DIR__ . "/unit/QTestForm.tpl.php");

