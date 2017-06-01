<?php
/**
 * Primitives to broadcast changes. These use the watcher mechanism. Override if you want something different or more
 * granular.
 */

if (count($objTable->PrimaryKeyColumnArray) == 1) {
	$pkType = $objTable->PrimaryKeyColumnArray[0]->VariableType;
} else {
	$pkType = 'string';	// combined pk
}

?>

   /**
	* The current record has just been inserted into the table. Let everyone know.
	* @param <?= $pkType ?>	$pk Primary key of record just inserted.
	*/
	protected static function broadcastInsert($pk)
    {
        if (static::$blnWatchChanges) {
            \QCubed\Project\Watcher\Watcher::markTableModified (static::getDatabase()->Database, '<?= $objTable->Name ?>');
        }
	}

   /**
	* The current record has just been updated. Let everyone know. $this->__blnDirty has the fields
    * that were just updated.
	* @param <?= $pkType ?>	$pk Primary key of record just updated.
	* @param string[] $fields array of field names that were modified.
	*/
	protected static function broadcastUpdate($pk, $fields)
    {
        if (static::$blnWatchChanges) {
            \QCubed\Project\Watcher\Watcher::markTableModified (static::getDatabase()->Database, '<?= $objTable->Name ?>');
        }
	}

   /**
	* The current record has just been deleted. Let everyone know.
	* @param <?= $pkType ?>	$pk Primary key of record just deleted.
	*/
	protected static function broadcastDelete($pk)
    {
        if (static::$blnWatchChanges) {
            \QCubed\Project\Watcher\Watcher::markTableModified (static::getDatabase()->Database, '<?= $objTable->Name ?>');
        }
	}

   /**
	* All records have just been deleted. Let everyone know.
	*/
	protected static function broadcastDeleteAll()
    {
        if (static::$blnWatchChanges) {
            \QCubed\Project\Watcher\Watcher::markTableModified (static::getDatabase()->Database, '<?= $objTable->Name ?>');
        }
	}