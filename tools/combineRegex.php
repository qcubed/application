#!/usr/bin/env php
<?php
/**
 * Tool to combine all the generated search and replace files into one.
 *
 */

$dirQCubed= dirname(dirname(__DIR__));

$files = [
    __DIR__ . '/manualChanges.regex.php',
    $dirQCubed . '/common/tools/common.regex.php',
    $dirQCubed . '/orm/tools/orm.regex.php',

];

// declare possible top-level items here
$aNew = [
    'const'=>[],
    'class'=>[],
    'func'=>[]
];
foreach ($files as $file) {
    $a = include($file);
    foreach ($a as $key=>$items) {
        $aNew[$key] = array_merge($aNew[$key], $items);
    }
}

return var_dump($aNew);