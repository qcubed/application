<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div id="instructions">
		<h1>QCubed Query Conditions</h1>
		
		<p>All <strong>QCubed Query</strong> method calls require a <strong>\QCubed\Query\QQ Condition</strong>. <strong>\QCubed\Query\QQ Conditions</strong> allow you
		to create a nested/hierarchical set of conditions to describe what essentially becomes your
		WHERE clause in a SQL query statement.</p>

		<p>The following is the list of \QCubed\Query\QQ Condition classes and what parameters they take:</p>
		<ul>
			<li>\QCubed\Query\QQ::All()</li>
			<li>\QCubed\Query\QQ::None()</li>
			<li>\QCubed\Query\QQ::Equal(\QCubed\Query\Node\NodeBase, Value)</li>
			<li>\QCubed\Query\QQ::NotEqual(\QCubed\Query\Node\NodeBase, Value)</li>
			<li>\QCubed\Query\QQ::GreaterThan(\QCubed\Query\Node\NodeBase, Value)</li>
			<li>\QCubed\Query\QQ::LessThan(\QCubed\Query\Node\NodeBase, Value)</li>
			<li>\QCubed\Query\QQ::GreaterOrEqual(\QCubed\Query\Node\NodeBase, Value)</li>
			<li>\QCubed\Query\QQ::LessOrEqual(\QCubed\Query\Node\NodeBase, Value)</li>
			<li>\QCubed\Query\QQ::IsNull(\QCubed\Query\Node\NodeBase)</li>
			<li>\QCubed\Query\QQ::IsNotNull(\QCubed\Query\Node\NodeBase)</li>
			<li>\QCubed\Query\QQ::In(\QCubed\Query\Node\NodeBase, array of string/int/datetime)</li>
			<li>\QCubed\Query\QQ::Like(\QCubed\Query\Node\NodeBase, string)</li>
		</ul>
		
		<p>For almost all of the above <strong>\QCubed\Query\QQ Conditions</strong>, you are comparing a column with some value.  The <strong>\QCubed\Query\QQ Node</strong> parameter
		represents that column.  However, value can be either a static value (like an integer, a string, a datetime, etc.)
		<i>or</i> it can be another <strong>\QCubed\Query\QQ Node</strong>.</p>
		
		<p>And finally, there are three special <strong>\QCubed\Query\QQ Condition</strong> classes which take in any number of additional <strong>\QCubed\Query\QQ Condition</strong> classes:</p>
		<ul>
			<li>\QCubed\Query\QQ::AndCondition()</li>
			<li>\QCubed\Query\QQ::OrCondition()</li>
			<li>\QCubed\Query\QQ::Not() - "Not" can only take in one <strong>\QCubed\Query\QQ Condition</strong> class</li>
		</ul>
		<p>(conditions can be passed in as parameters and/or as arrays)</p>
		
		<p>Because And/Or/Not conditions can take in <i>any</i> other condition, including other And/Or/Not conditions, you can
		embed these conditions into other conditions to create what ends up being a logic tree for your entire SQL Where clause.  See
		below for more information on this.</p>
	</div>

<div id="demoZone">
	<h2>Select all People where: the first name is alphabetically "greater than" the last name</h2>
	<ul>
<?php
	$objPersonArray = Person::QueryArray(
		// Notice how we are comparing to \QCubed\Query\QQ Column Nodes together
		\QCubed\Query\QQ::GreaterThan(QQN::Person()->FirstName, QQN::Person()->LastName)
	);

	foreach ($objPersonArray as $objPerson){
		_p('<li>'.$objPerson->FirstName . ' ' . $objPerson->LastName.'</li>', false);
	}
?>
	</ul>
	<h2>Select all Projects where: the manager's first name is alphabetically "greater than" the last name, or who's name contains "Website"</h2>
	<ul>
<?php
	$objProjectArray = Project::QueryArray(
		\QCubed\Query\QQ::OrCondition(
			\QCubed\Query\QQ::GreaterThan(QQN::Project()->ManagerPerson->FirstName, QQN::Project()->ManagerPerson->LastName),
			\QCubed\Query\QQ::Like(QQN::Project()->Name, '%Website%')
		)
	);

	foreach ($objProjectArray as $objProject) {
		_p(sprintf('<li>%s (managed by %s %s)</li>', $objProject->Name, $objProject->ManagerPerson->FirstName, $objProject->ManagerPerson->LastName), false);
	}
?>
	</ul>
	<h2>Select all Projects where: the Project ID <= 2 AND (the manager's first name is alphabetically "greater than" the last name, or who's name contains "Website")</h2>
	<ul>
<?php
	$objProjectArray = Project::QueryArray(
		\QCubed\Query\QQ::AndCondition(
			\QCubed\Query\QQ::OrCondition(
				\QCubed\Query\QQ::GreaterThan(QQN::Project()->ManagerPerson->FirstName, QQN::Project()->ManagerPerson->LastName),
				\QCubed\Query\QQ::Like(QQN::Project()->Name, '%Website%')
			),
			\QCubed\Query\QQ::LessOrEqual(QQN::Project()->Id, 2)
		)
	);

	foreach ($objProjectArray as $objProject) {
		_p(sprintf('<li>%s (managed by %s %s)</li>', $objProject->Name, $objProject->ManagerPerson->FirstName, $objProject->ManagerPerson->LastName), false);
	}
?>
	</ul>
</div>

<?php require('../includes/footer.inc.php'); ?>