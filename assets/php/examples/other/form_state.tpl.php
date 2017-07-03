<?php require('../includes/header.inc.php'); ?>
<?php $this->renderBegin(); ?>

<div id="instructions">
	<h1>Customizing How FormState is Saved</h1>

	<p>By default, the <strong>Form</strong> engine will store the state of the actual <strong>Form</strong> objects as a rather
		long <strong>Base64</strong> encoded string.  While this is a very simple
		approach, it is not secure, can gradually produce performance problems as the formstate grows, and may reach size limitations.
      </p>
	<p>QCubed resolves this by offering the ability to store/handle the formstate in various ways.  You can store
		the formstate data in PHP Sessions, in a database, in an external cache, or directly on the
		filesystem.  For all of these methods, you end up only passing a small key back to the user so the form can keep track
        of the location of the data.
    </p>

	<p>Since the FormState handler is encapsulated in its own class, you can even define your own formstate
		handler.</p>

	<p>In our example below, we use <strong>SessionHandler</strong> to store the formstate data in PHP Session, and we
		will only store the session key (in this case, just a simple integer) on the page as a hidden form variable.
		For an added level of security, we will also encrypt the key.</p>

	<p>If you use your browser's "View Source" functionality, you will see that the <strong>Qform__FormState</strong> hidden
		form variable is now a <strong>lot</strong> shorter (likely about 20 bytes).  Compare this to the
		<a href="../basic_qform/intro.php" class="bodyLink">first example</a> where the form state was easily over 1 KB.  This is because
		the bulk of the form state is being stored as a PHP Session Variable, which is located on the server, itself.</p>
</div>

<div id="demoZone">
	<?php
	// We will override the ForeColor, FontBold and the FontSize.  Note how we can optionally
	// add quotes around our value.
	?>
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->renderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>