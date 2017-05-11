/**
     * This will DELETE this object's <?= $objTable->ClassName; ?> instance from the database.
     * It will also unassociate itself from any ManyToManyReferences.
     */
    public function delete<?= $objTable->ClassName; ?>()
    {
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
        $this-><?= $objCodeGen->ModelVariableName($objTable->Name) ?>->unassociateAll<?= $objManyToManyReference->ObjectDescriptionPlural ?>();
<?php } ?>
        $this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->delete();
    }