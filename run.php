<?php

namespace App;

// Usage: php run.php <day> <part> [inputFile]
require __DIR__ . '/vendor/autoload.php';

$day = $argv[1] ?? null;
$part = $argv[2] ?? null;
$inputPath = $argv[3] ?? "inputs/$day.txt";

if (!$day || !$part) {
    fwrite(STDERR, "Usage: php run.php <day> <part> [inputFile]\n");
    exit(1);
}

$dayClass = "App\\Days\\Day$day";


if (!class_exists($dayClass)) {
    fwrite(STDERR, "Day $day not found\n");
    exit(1);
}

if (!file_exists($inputPath)) {
    fwrite(STDERR, "Input file not found: $inputPath\n");
    exit(1);
}

$input = trim(file_get_contents($inputPath));

$instance = new $dayClass();

if (!method_exists($instance, $part)) {
    fwrite(STDERR, "Method for part $part not found (expected one() or two())\n");
    exit(1);
}

if (method_exists($instance, 'parse')) {
    $input = $instance->parse($input);
}

$result = $instance->{$part}($input);
if (is_string($result)) {
    echo $result . PHP_EOL;
}
else {
    var_dump($result);
}