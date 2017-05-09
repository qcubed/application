<?php require('../includes/header.inc.php'); ?>
<?php $this->renderBegin(); ?>

<div id="instructions">
	<h1>\QCubed\Project\Control\JsTimer - Qcubed JavaScript Timer</h1>

	<p> In the following example \QCubed\Project\Control\JsTimer is used to update a datagrid displaying the 
	incoming orders of a fictional web shop.<br/>
	\QCubed\Project\Control\JsTimer can be used as a periodic or a one-shot timer.
	This example shows the periodic one.</p>

	<p>Take a look at the example below. The update timer <strong>$ctlTimer</strong>
	is created with <strong>$this->ctlTimer = new \QCubed\Project\Control\JsTimer($this,3000,true,true);</strong>
	The second parameter sets the update interval (in ms),the third parameter defines 
	a periodic timer and the fourth parameter tells the timer to start automatically after the first action is added.
	If you want the timer to stop after it has fired, set the third parameter to false or call <strong>->Periodic = false</strong>
	on your timer instance. You can restart the timer by simply calling <strong>->Start($intNewTime)</strong>. Without parameter 
	the old delay/interval is used.</p>

	<p>You can add actions to \QCubed\Project\Control\JsTimer like you would do for any other control.
	There is one limitation:<br/>
	\QCubed\Project\Control\JsTimer accepts only one type of Event: <strong>\QCubed\Event\TimerExpired</strong>,
	adding multiple actions to one Event is possible.</p>

	<p>Look at: <strong>$this->ctlTimer->AddAction(new \QCubed\Event\TimerExpired(), new \QCubed\Action\Ajax('OnUpdateDtg'));</strong>
	When the defined time has passed by, the <strong>OnUpdateDtg</strong> method is called. In this method you could
	fetch new orders from a database or from a web-service ... 
	for this example, adding an order to an array and marking the datagrid <strong>$dtgOrders</strong>
	as modified, should be sufficient.</p>

	<p>If you look at the template file you will notice that there is no <strong>$ctlTimer->Render()</strong>.
	That is where \QCubed\Project\Control\JsTimer differs from other controls. There is no need to render it!</p>

	<p>If a server action is executed, the whole page gets reloaded. So all JavaScript is stopped and
	the timer is stopped too! In the case of our web shop we want to get informed about new orders after a server
	action. To address this problem you can set the parameter <strong>RestartOnServerAction</strong>
	of \QCubed\Project\Control\JsTimer to <strong>true</strong>.</p>
</div>

<div id="demoZone">
<?php
	//$this->ctlTimer->Render();
	$this->btnRestartOnServerAction->Render();
	$this->btnStart->Render(); 
	$this->btnStop->Render();
	$this->btnServerAction->Render();
?>
	<?php $this->dtgOrders->Render(); ?>
</div>
	<?php $this->renderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>