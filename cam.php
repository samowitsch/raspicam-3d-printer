<?php
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
    $diskUsageItems = array_filter(explode(chr(10),$diskUsage));
}

$enabled = false;
if (file_exists('ENABLED')) {
    $enabled = true;
}

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'debug' => true,
    'cache' => 'compilation_cache',
));

$template = $twig->load('cam.twig');

echo $template->render(
    [
        'recording' => $enabled,
        'time' => time(),
        'temp' => shell_exec('sudo vcgencmd measure_temp'),
        'clock' => shell_exec('sudo vcgencmd measure_clock arm'),
        'diskUsage' => $diskUsage,
        'diskUsageItems' => $diskUsageItems,
    ]
);
