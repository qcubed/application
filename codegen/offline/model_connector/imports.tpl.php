<?php

use QCubed\Generator\Label;

// Array is an array of arrays, with each entry being an array of:
// class=>classname
// as (optional) => as name
$imports = [];
foreach ($objTable->ColumnArray as $objColumn) {
    if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;

    $objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
    $controlImports = $objControlCodeGenerator->connectorImports($objCodeGen, $objTable, $objColumn);
    $imports = array_merge($imports, $controlImports);

    if ($objControlCodeGenerator->GetControlClass() != 'Label' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == QFormGen::Both)) {
        // also generate a QLabel for each control that generates both
        $controlImports = Label::instance()->connectorImports($objCodeGen, $objTable, $objColumn);
        $imports = array_merge($imports, $controlImports);
    }
}


foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
    if (!$objReverseReference->Unique) continue;
    if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;

    $objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objReverseReference);
    $controlImports = $objControlCodeGenerator->connectorImports($objCodeGen, $objTable, $objReverseReference);
    $imports = array_merge($imports, $controlImports);

    if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] == QFormGen::Both)) {
        // also generate a QLabel for each control that generates both
        $controlImports = Label::instance()->connectorImports($objCodeGen, $objTable, $objReverseReference);
        $imports = array_merge($imports, $controlImports);
    }
}

foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
    if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;

    $objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objManyToManyReference);
    $controlImports = $objControlCodeGenerator->connectorImports($objCodeGen, $objTable, $objManyToManyReference);
    $imports = array_merge($imports, $controlImports);

    if ($objControlCodeGenerator->GetControlClass() != 'QLabel' && (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] == QFormGen::Both)) {
        // also generate a QLabel for each control that generates both
        $controlImports = Label::instance()->connectorImports($objCodeGen, $objTable, $objManyToManyReference);
        $imports = array_merge($imports, $controlImports);

    }
}

// Consolidate the various imports found, throwing exceptions on any conflicts
$outImports = [];
foreach ($imports as $import) {
    if (!isset($outImports[$import['class']])) {
        $outImports[$import['class']] = $import;
    }
    elseif (array_diff_assoc($outImports[$import['class']], $import)) {
        throw new \Exception ('Found incompatible namespace imports: ' . $import['class']);
    }
}

// Now output them
foreach ($outImports as $import) {
    echo "use " . $import['class'];
    if (!empty($import['as'])) {
        echo ' as ' . $import['as'];
    }
    echo ';';
    echo "\n";
}