<?php

require 'vendor/autoload.php';

use JasonGrimes\Paginator;
use MotionsMedia\RaspiPrinterCam\InfoUtility;

if (isset($_GET['delete'])) {
    $mp4 = './timelap/' . $_GET['delete'];
    $jpg = './timelap-thumbs/' . str_replace('.mp4', '.jpg', $_GET['delete']);

    if (file_exists($mp4)) {
        unlink($mp4);
    }
    if (file_exists($jpg)) {
        unlink($jpg);
    }

    $message = '
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Deleted:</span>
  Deleted file: ' . $_GET['delete'] . '
  </div>';
}

$files = scandir('timelap', SCANDIR_SORT_DESCENDING);
#$files = array_slice($files, 0);

$totalItems = (count($files) - 2);
$itemsPerPage = 50;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$urlPattern = 'timelap-archive.php?page=(:num)';

$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

$subFiles = array_slice($files, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);

$diskUsage = '';
$diskUsageItems = [];
if (file_exists('usage.txt')) {
    //$diskUsage = '<p>' . nl2br(file_get_contents('usage.txt')) . '</p>';
    $diskUsage = file_get_contents('usage.txt');
    $diskUsageItems = array_filter(explode(chr(10), $diskUsage));
}

$newSubFiles = [];
foreach ($subFiles as $key => $file) {
    if ($file == '..' || $file == '.') {
        continue;
    }
    $f = new \stdClass();
    $f->rendering = false;
    $thumb = 'timelap-thumbs/' . str_replace('.mp4', '.jpg', $file);
    if (!file_exists($thumb)) {
        $thumb = 'static/pics/waiting.gif';
        $f->rendering = true;
    }

    $f->name = $file;
    $f->thumb = $thumb;
    $f->file = 'timelap/' . $file;
    array_push($newSubFiles, $f);
}

$loader = new Twig\Loader\FilesystemLoader('templates');
$twig = new Twig\Environment($loader, array(
    'debug' => true,
    'cache' => 'compilation_cache',
));

$template = $twig->load('timelap-archive.twig');

echo $template->render(
    [
        'paginator' => $paginator,
        'files' => $files,
        'subFiles' => $newSubFiles,
        'totalItems' => $totalItems,
        'itemsPerPage' => $itemsPerPage,
        'currentPage' => $currentPage,
        'diskUsage' => $diskUsage,
        'message' => $message,
        'cpuInfo' => InfoUtility::getCpuInfo(),
        'diskInfo' => InfoUtility::getDiskUsage(),
        'cameraInfo' => InfoUtility::getCameraInfo(),
    ]
);
