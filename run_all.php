<?php

namespace App;

use DirectoryIterator;

// Usage: php run_all.php
require __DIR__ . '/vendor/autoload.php';

$inputsByDay = [];
foreach (new DirectoryIterator(__DIR__ . '/inputs') as $file) {
    if ($file->isFile() && $file->getExtension() === 'txt') {
        $day = $file->getBasename('.txt');
        $inputsByDay[$day] = file_get_contents($file->getPathname());
    }
}

echo "Day\t|\tone\t|\ttwo" . PHP_EOL;

$min = 1;
$max = $inputsByDay |> array_keys(...) |> max(...);
for ($day = $min; $day <= $max; ++$day) {
    $oneStatus = '-';
    $twoStatus = '-';
    $dayClass = "App\\Days\\Day$day";
    if (class_exists($dayClass)) {
        $instance = new $dayClass();
        $input = trim($inputsByDay[$day]);
        if (method_exists($instance, 'parse')) {
            $input = $instance->parse($input);
        }
        if (method_exists($instance, 'one')) {
            $instance->one($input);
            $oneStatus = '✅';
        }
        if (method_exists($instance, 'two')) {
            $instance->two($input);
            $twoStatus = '✅';
        }
    }
    echo " $day\t|\t$oneStatus\t|\t$twoStatus " . PHP_EOL;
}

echo "Done" . PHP_EOL;