<?php

require 'vendor/autoload.php';

use JasonGrimes\Paginator;
use MotionsMedia\RaspiPrinterCam\InfoUtility;

if (isset($_GET['delete'])) {
    unlink('./archive/' . $_GET['delete']);
    if (file_exists('./archive-thumbs/' . $_GET['delete'])) {
        unlink('./archive-thumbs/' . $_GET['delete']);
    }
    $message = '
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Deleted:</span>
  Deleted file: ' . $_GET['delete'] . '
</div>';
}

$files = scandir('archive', SCANDIR_SORT_DESCENDING);
#$files = array_slice($files, 0);

$totalItems = (count($files) - 2);
$itemsPerPage = 50;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$urlPattern = 'cam-archive.php?page=(:num)';

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
    if ($file === '..' || $file === '.') {
        continue;
    }
    $f = new \stdClass();
    $f->name = $file;
    $f->file = 'archive/' . $file;
    $f->thumbPath = file_exists('archive-thumbs/' . $file) ?
        'archive-thumbs/' . $file :
        'archive/' . $file;
    array_push($newSubFiles, $f);
}

$loader = new Twig\Loader\FilesystemLoader('templates');
$twig = new Twig\Environment($loader, array(
    'debug' => true,
    'cache' => 'compilation_cache',
));

$template = $twig->load('archive.twig');

echo $template->render(
    [
        'paginator' => $paginator,
        'files' => $files,
        'subFiles' => $newSubFiles,
        'totalItems' => $totalItems,
        'itemsPerPage' => $itemsPerPage,
        'currentPage' => $currentPage,
        'diskUsage' => $diskUsage,
        'diskUsageItems' => $diskUsageItems,
        'message' => $message,
        'cpuInfo' => InfoUtility::getCpuInfo(),
    ]
);
