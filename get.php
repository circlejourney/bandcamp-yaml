<?php
	header("Content-Type: text/plain");
    require_once("phpQuery/phpQuery.php");
    $today = new DateTime();
    $url = $_GET["url"];
    $host = (parse_url($url))["host"];

    phpQuery::newDocumentHTML(file_get_contents($url));

    $title = trim( pq("#name-section .trackTitle")->text() );
    $commentary = preg_replace("/\n\s*/", "\n\t", trim( pq(".tralbum-about")->text() ) );
    $albumArt = trim( pq(".popupImage img")->attr("src") );
    preg_match("/released\s(.*?)\n/", pq(".tralbum-credits")->text(), $releasematch);
    $release = trim( $releasematch[1] );
    
    $rows = pq(".track_row_view");

?>
Album: <?php echo $title; ?>

Date: <?php echo $release ?>
Date Added: <?php echo date( "F d, Y" ); ?>

# Album art URL: <?php echo $albumArt; ?>

URLs:
- <?php echo $url ?>

Commentary: |-
	<?php echo $commentary ?>

<?php
foreach($rows as $i => $row): 
    $title = trim( pq($row)->find(".track-title")->text() );
    $duration = trim( pq($row)->find(".time")->text() );
    $lyrics = trim( str_replace("\n", "\n\t", pq($row)->next(".lyricsRow")->text()) );
    $trackurl = "https://" . $host . pq($row)->find(".title a")->attr("href");
	if($trackpage = file_get_contents($trackurl)) {
		phpQuery::newDocumentHTML( file_get_contents($trackurl) );
		$credits = preg_replace("/\n\s+/", "\n", pq(".tralbum-credits")->text());
		$credits = preg_replace("/(^|\n)from\s[\S\s]*,\sreleased.*/", "", $credits);
		$trackcommentary = preg_replace("/\n\s+\b/", "\n\t", pq(".tralbum-about")->text());
		$trackdata = [
			"commentary" =>  trim( $trackcommentary ),
			"track_art" => trim( pq(".popupImage img")->attr("src") ),
			"credits" => trim( $credits )
		];
	}
?>
---
Track: <?php echo $title; ?>

Duration: '<?php echo $duration; ?>'
URLs:
- <?php echo $trackurl ?>

<?php if(strlen($lyrics) > 0): ?>
Lyrics: |-
	<?php echo $lyrics ?>

<?php endif?><?php if(isset($trackdata) && strlen($trackdata["commentary"]) > 0): ?>
Commentary: |-
	<?php echo preg_replace("/\n\s+/", "\n\t", $trackdata["commentary"]) ?>

<?php endif;	if(isset($trackdata) && strlen($trackdata["track_art"]) > 0 && $trackdata["track_art"] !== $albumArt): ?>

# Track Art URL: <?php echo $trackdata["track_art"] ?>

<?php endif;	if(isset($trackdata) && strlen($trackdata["credits"]) > 0): ?>

# Credits:
<?php echo preg_replace("/(^|\n)/", "$1# ", $trackdata["credits"]) ?>

<?php endif?>
<?php endforeach ?>