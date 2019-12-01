<?php
/*
 * make video from pictures:
 * ffmpeg -f concat -i files-of-range.txt -vsync vfr -pix_fmt yuv420p -vf scale=480:-1 video.mp4
 *
 * make thumbnaill from video:
 * ffmpeg -ss 3 -i 0007.mp4 -t 1 -s 480x360 -f image2 ../timelap-thumbs/0007.jpg
 *
 */
require 'vendor/autoload.php';

$files = scandir('archive', SCANDIR_SORT_DESCENDING);
$filesJson = json_encode($files, JSON_PRETTY_PRINT);
$filesCount = (count($files) - 2);

$diskUsage = '';
$diskUsageItems = [];
if (file_exists('usage.txt')) {
    //$diskUsage = '<p>' . nl2br(file_get_contents('usage.txt')) . '</p>';
    $diskUsage = file_get_contents('usage.txt');
    $diskUsageItems = array_filter(explode(chr(10),$diskUsage));
}

$range = [1,$filesCount];
if (isset($_GET['range'])) {
	$range = explode(',', urldecode($_GET['range']));
	list($from, $to) = $range;
	if (((int) $to - (int) $from) < 60) {
	        $message = '
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Hinweis:</span>
  Bitte wählen Sie mehr als 60 Bilder aus!
</div>';

	} else {
	    $fileList = [];
	    for ($i = $from; $i <= $to - 1; $i++ ) {
		    $fileList[] = sprintf("file 'archive/%s'", $files[$i]);
	    }
	    if (count($fileList)) {
		    $list = array_reverse($fileList);
		    $filename = 'TIMELAP.txt';
		    file_put_contents($filename, implode(PHP_EOL . 'duration 0.05' . PHP_EOL , $list));
		    chmod($filename, 0777);

		    // redirect to remove get params to prevent double renderings on page reload.
            header("Location: timelap.php");
            exit;
	    }
	}

}

$range = json_encode($range, JSON_PRETTY_PRINT);

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'debug' => true,
    'cache' => 'compilation_cache',
	));

$template = $twig->load('timelap.twig');

function checkRenderStatus(){
    return file_exists('TIMELAP.txt') || file_exists('TIMELAP-RENDERING.txt');
}
function getRenderData (){
    return file_get_contents('TIMELAP-RENDERING.txt');
}

echo $template->render(
	[
	    'range' => $range,
	    'message' => $message,
	    'filesCount' => $filesCount,
	    'filesJson' => $filesJson,
	    'renderFileExists' => checkRenderStatus(),
	    'renderFileData' => (checkRenderStatus()) ? getRenderData() : '',
        'temp' => shell_exec('sudo vcgencmd measure_temp'),
        'clock' => shell_exec('sudo vcgencmd measure_clock arm'),
        'diskUsage' => $diskUsage,
        'diskUsageItems' => $diskUsageItems,
	]
);
