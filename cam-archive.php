<?php
require 'vendor/autoload.php';

use JasonGrimes\Paginator;

if (isset($_GET['delete'])) {
    unlink('./archive/' . $_GET['delete']);
    $message = '
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Deleted:</span>
  Deleted: archive/' . $_GET['delete'] . '
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

$du = '';
if (file_exists('usage.txt')) {
    $du = '<p>' . nl2br(file_get_contents('usage.txt')) . '</p>';
}
?>


<html>
    <head>
	<title>Raspicam1</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity
	      ="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">
	<link href="style.css" rel="stylesheet">
    </head>
    <body>
	<h1>Raspicam 1 archive  [<?php echo $totalItems ?> pic(s)]</h1>
	<p><a class="btn btn-default"  href="cam.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück</a></p>

	<?php
	echo '<p>' . $paginator . '</p>';
	echo '<div class="thumb-container popup-gallery">';
	foreach ($subFiles as $k => $f) {
	    switch ($f) {
		case ".":
		case "..":
		    continue;
		default:

		    $fp = 'archive/' . $f;
		    $fpt = 'archive-thumbs/' . $f;
		    if (file_exists($fpt)) {
			$ii = getimagesize($fpt);
			$fs = number_format(filesize($fpt) / 1024, 2, ',', '.');
			$fi = $f . ' (' . $ii[0] . 'x' . $ii[1] . 'px / ' . $fs . 'kb)';
			$fp = $fpt;
		    } elseif (file_exists($fp)) {
			$ii = getimagesize($fp);
			$fs = number_format(filesize($fp) / 1024, 2, ',', '.');
			$fi = $f . ' (' . $ii[0] . 'x' . $ii[1] . 'px / ' . $fs . 'kb)';
		    }
		    echo '<div class="thumb-wrap">
				<a href="archive/' . $f . '"><img class="thumbs" title="' . $fi . '" src="' . $fp . '"/></a>
				<div class="btn-group"><button class="btn btn-danger btn-sm delete" data-href="cam-archive.php?page=' . $currentPage . '&delete=' . $f . '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></div>
				</div>';


		    break;
	    }
	}

	echo '</div>';
	echo '<p>' . $paginator . '</p>';
	?>

	<p><a class="btn btn-default" href="cam.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> zurück</a></p>
	<div class="alert alert-info" role="alert">
	    <h4>Disc usage:</h4>
	    <?php echo $du; ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib02
	7qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="script.js"></script>
    </body>
</html>

