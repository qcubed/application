	/**
	 *	Bind Data to the list control.
	 **/
	public function bindData() {
		$objCondition = $this->getCondition();
		$this-><?= $strListVarName ?>->bindData($objCondition);
	}


	/**
	 *  Get the condition for the data binder.
	 *  @return QQCondition;
	 **/
	protected function getCondition() {
<?php if (isset($objTable->Options['CreateFilter']) && $objTable->Options['CreateFilter'] === false) { ?>
		return \QCubed\Query\QQ::all();
<?php } else { ?>
		$strSearchValue = $this->txtFilter->Text;
		$strSearchValue = trim($strSearchValue);

		if (is_null($strSearchValue) || $strSearchValue === '') {
			 return \QCubed\Query\QQ::all();
		} else {
<?php
		$cond = array();
		foreach ($objTable->ColumnArray as $objColumn) {
			switch ($objColumn->VariableTypeAsConstant) {
				case '\QCubed\Type::INTEGER':
					$cond[] = '\QCubed\Query\QQ::equal(QQN::' . $objTable->ClassName . '()->' . $objColumn->PropertyName . ', $strSearchValue)';
					break;
				case '\QCubed\Type::STRING':
					$cond[] = '\QCubed\Query\QQ::like(QQN::' . $objTable->ClassName . '()->' . $objColumn->PropertyName. ', "%" . $strSearchValue . "%")';
					break;
			}
		}

		$strCondition = implode (",\n            ", $cond);
		if ($strCondition) {
			$strCondition = "\QCubed\Query\QQ::orCondition(
				$strCondition
			)";
		} else {
			$strCondition = '\QCubed\Query\QQ::all()';
		}
?>
			return <?= $strCondition ?>;
<?php } ?>
		}

	}

