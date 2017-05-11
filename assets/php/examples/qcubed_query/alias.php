<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>SQL Aliases for QQuery</h1>

	<p>Up until now, we've only described doing simple joins with QQuery, where each
	table is only needed for a single related record. But what about when you need
	to check on multiple entries in the same joined table. In standard SQL, you
	would join the table a second time, giving it a new name. And that's exactly
	how you do it in QQuery as well, using <strong>\QCubed\Query\QQ::Alias</strong>.</p>

	<p>In the example 1 below, we need to find who belongs to both the ACME Website
	Redesign and the State College HR System projects. We do this simply by joining
	the membership association table twice, each time with a different alias. This
	allows us to create a condition that says that one associated project has an
	ID of 1, while a <em>different</em> associated project also has an ID of 2.</p>

	<p>In a slightly more complex example 2 below, we are looking for all projects that
	are associated with two other projects (each is specified by name). We use the
	same technique with <b>\QCubed\Query\QQ::Alias()</b> as in example 1, except that we now
	mix it in with relationships expanded to other tables.</p>
</div>

<div id="demoZone">
	<h2>Example 1: Project members whose are in both project 1 and 2</h2>
<?php
	\QCubed\Database\Service::getDatabase(1)->EnableProfiling();

	$objPersonArray = Person::QueryArray(
		\QCubed\Query\QQ::AndCondition(
				\QCubed\Query\QQ::Equal(\QCubed\Query\QQ::Alias(QQN::Person()->ProjectAsTeamMember, 'pm1')->ProjectId, 1),
				\QCubed\Query\QQ::Equal(\QCubed\Query\QQ::Alias(QQN::Person()->ProjectAsTeamMember, 'pm2')->ProjectId, 2)
			)
		);

	foreach ($objPersonArray as $objPerson){
		_p($objPerson->FirstName . ' ' . $objPerson->LastName);
		_p('<br/>', false);
	}
?>

	<h2>Example 2: Projects that are related to both 'Blueman Industrial Site Architecture' and 'ACME Payment System' projects</h2>
<?php
	$objProjectArray = Project::QueryArray(
		\QCubed\Query\QQ::AndCondition(
				\QCubed\Query\QQ::Equal(\QCubed\Query\QQ::Alias(QQN::Project()->ProjectAsRelated, 'related1')->Project->Name, 'Blueman Industrial Site Architecture'),
				\QCubed\Query\QQ::Equal(\QCubed\Query\QQ::Alias(QQN::Project()->ProjectAsRelated, 'related2')->Project->Name, 'ACME Payment System')
			)
		);

	foreach ($objProjectArray as $objProject){
		_p($objProject->Name . " (" . $objProject->Description . ")");
		_p('<br/>', false);
	}
?>

	<h2>Example 3: Managers having one least a project with a conson milestone, and for each manager, the first voyel milestone and the first conson one</h2>
<?php
	$emptySelect = \QCubed\Query\QQ::Select();
	$emptySelect->SetSkipPrimaryKey(true);
	$nVoyel = \QCubed\Query\QQ::Alias(QQN::Person()->ProjectAsManager->Milestone, 'voyel');
	$nConson = \QCubed\Query\QQ::Alias(QQN::Person()->ProjectAsManager->Milestone, 'conson');
	$objPersonArray = Person::QueryArray(
		\QCubed\Query\QQ::IsNotNull($nConson->Id),
		\QCubed\Query\QQ::Clause(
			\QCubed\Query\QQ::Expand(QQN::Person()->ProjectAsManager, null, $emptySelect),
			\QCubed\Query\QQ::Expand($nVoyel, \QCubed\Query\QQ::In($nVoyel->Name, array('Milestone A', 'Milestone E', 'Milestone I')), $emptySelect),
			\QCubed\Query\QQ::Expand($nConson, \QCubed\Query\QQ::NotIn($nConson->Name, array('Milestone A', 'Milestone E', 'Milestone I')), $emptySelect),
			\QCubed\Query\QQ::GroupBy(QQN::Person()->Id),
			\QCubed\Query\QQ::Minimum($nVoyel->Name, 'min_voyel'),
			\QCubed\Query\QQ::Minimum($nConson->Name, 'min_conson'),
			//*** only needed in PG-SQL.
			// Even with an empty select, id is selected;
			// Happily, PG doesn't complain if both id and MIN(id) are selected
			\QCubed\Query\QQ::Expand(QQN::Person()->ProjectAsManager, null, $emptySelect),
			\QCubed\Query\QQ::Minimum(QQN::Person()->ProjectAsManager->Id, 'dummy'),
			//***
			\QCubed\Query\QQ::Select(
				QQN::Person()->FirstName,
				QQN::Person()->LastName
			)
		)
	);

	foreach ($objPersonArray as $objManager){
		_p($objManager->FirstName.' '.$objManager->LastName. " (" . $objManager->GetVirtualAttribute('min_voyel').', '. $objManager->GetVirtualAttribute('min_conson'). ")");
		_p('<br/>', false);
	}
?>

	<h2>Example 4: Projects with, for each one, the "min" city from the addresses containing 'r' and the "min" city from the addresses NOT containing 'r' </h2>
<?php
	$nWithR = \QCubed\Query\QQ::Alias(QQN::Project()->PersonAsTeamMember->Person->Address, 'with_r');
	$nWithoutR = \QCubed\Query\QQ::Alias(QQN::Project()->PersonAsTeamMember->Person->Address, 'without_r');
	$objProjectArray = Project::QueryArray(
		\QCubed\Query\QQ::All(),
		\QCubed\Query\QQ::Clause(
			\QCubed\Query\QQ::Expand($nWithR, \QCubed\Query\QQ::Like($nWithR->Street, '%r%')),
			\QCubed\Query\QQ::Expand($nWithoutR, \QCubed\Query\QQ::NotLike($nWithoutR->Street, '%r%')),
			\QCubed\Query\QQ::GroupBy(QQN::Project()->Id),
			\QCubed\Query\QQ::Minimum($nWithR->City, 'min_city_r'),
			\QCubed\Query\QQ::Minimum($nWithoutR->City, 'min_city_wor')
		)
	);

	foreach ($objProjectArray as $objProject){
		_p($objProject->Name . " (" . $objProject->GetVirtualAttribute('min_city_r').', '. $objProject->GetVirtualAttribute('min_city_wor'). ")");
		_p('<br/>', false);
	}


	\QCubed\Database\Service::getDatabase(1)->OutputProfiling();
?>
</div>

<?php require('../includes/footer.inc.php'); ?>