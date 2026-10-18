<?php

declare(strict_types=1);

use MotionsMedia\RaspiPrinterCam\InfoUtility;

require 'vendor/autoload.php';

if (isset($_GET['enable'])) {
    switch ($_GET['enable']) {
        case '0':
            unlink('ENABLED');
            break;
        case '1':
            file_put_contents('ENABLED', 'x');
            break;
    }
}

$diskUsage = '';
$diskUsageItems = [];
if (file_exists('usage.txt')) {
    //$diskUsage = '<p>' . nl2br(file_get_contents('usage.txt')) . '</p>';
    $diskUsage = file_get_contents('usage.txt');
    $diskUsageItems = array_filter(explode(chr(10), $diskUsage));
}

$enabled = false;
if (file_exists('ENABLED')) {
    $enabled = true;
}

$loader = new Twig\Loader\FilesystemLoader('templates');
$twig = new Twig\Environment($loader, [
    'debug' => true,
    'cache' => 'compilation_cache',
]);

$template = $twig->load('cam.twig');

echo $template->render(
    [
        'recording' => $enabled,
        'processPid' => shell_exec('pgrep cam.sh') ?? 'background cam.sh not running?',
        'time' => time(),
        'cpuInfo' => InfoUtility::getCpuInfo(),
        'diskInfo' => InfoUtility::getDiskUsage(),
        'diskUsage' => $diskUsage,
        'diskUsageItems' => $diskUsageItems,
    ]
);
