<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>
<div id="instructions">
	<h1>Measuring Performance using \QCubed\Timer</h1>

	<p>If you ever need to do light-weight profiling of your QCubed application, 
		you might find the <strong>\QCubed\Timer</strong> class useful. It's really simple: you can start
		a named timer by doing <em>\QCubed\Timer::start('timerName')</em>; you can stop it; 
		you can restart the timer later if you want to. When you're done measuring 
		a section of your code, just call <em>\QCubed\Timer::getTime('timerName')</em>.
		If you had several timers running, an easy way to dump all the interesting 
		debug info is to call <em>\QCubed\Timer::varDump().</em></p>

	<p>Each of the timers is internall maintained as a \QCubed\Timer object. If you want to 
		know more about the timers - for example, the number of times the timer was 
		started - you can get the \QCubed\Timer object instance, and then interrogating that
		instance:<br>
	<pre><code>$objTimer = \QCubed\Timer::GetTimer('timerName');
echo $objTimer->CountStarted;</code></pre>				

	<p>Take a look at a sample usage example below by clicking View Source.</p>
</div>

<div id="demoZone">
<?php
	\QCubed\Timer::start('longCalculation');
	for ($i = 0; $i < 1000000; $i++) {
		// do nothing - just loop a bunch of times
	}
	\QCubed\Timer::stop('longCalculation');
	echo "Here's how long it took to execute the long calculation: " . \QCubed\Timer::getTime('longCalculation') . "<br /><br />";

	\QCubed\Timer::start('loadPersons');
	$arrPersons = Person::LoadAll();
	\QCubed\Timer::stop('loadPersons');

	// resume the long calculation timer			
	\QCubed\Timer::start('longCalculation');
	for ($i = 0; $i < 1000000; $i++) {
		// do nothing - just loop a bunch of times
	}
	\QCubed\Timer::stop('longCalculation');

	echo "<strong>Results of \QCubed\Timer::varDump():</strong><br>";
	\QCubed\Timer::varDump();
?>
</div>

<?php require('../includes/footer.inc.php'); ?>