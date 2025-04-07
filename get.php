<?php
    error_reporting(0);
	header("Content-Type: text/plain");
    require_once("phpQuery/phpQuery.php");
    
    $url = $_POST["url"];
    $track_artist = $_POST["track_artist"];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $raw = curl_exec($ch);
    unset($ch);

    phpQuery::newDocumentHTML($raw);

    // Get page meta
    $metadata = json_decode(pq("[type='application/ld+json']")->text(), true);

    // Parse meta
    $actual_url = $metadata["@id"];
    $commentary = str_replace( "\n", "\n    ",
        $metadata["description"]
    );
    $release = date("F j, Y", strtotime($metadata["datePublished"]));
?>
Album: <?php echo $metadata["name"] ?>

<?php if($_POST["one_musician"] == "on"): ?>
Artists:
- <?php echo $metadata["byArtist"]["name"] ?>

<?php endif ?><?php if($_POST["cover_artist"]): ?>
Cover Artists:
- <?php echo $_POST["cover_artist"]; ?>

<?php endif ?><?php if($_POST["track_artist"]): ?>
Default Track Cover Artists:
- <?php echo $_POST["track_artist"]; ?>

<?php endif ?>
Date: <?php echo $release ?>

Date Added: <?php echo date( "F d, Y" ) ?>

# Album art URL: <?php echo $metadata["image"] ?>

URLs:
- <?php echo $actual_url ?>

Commentary: |-
    <i>[NAME]:</i>
    <?php echo $commentary ?>

<?php
$tracks = $metadata["track"]["itemListElement"];
foreach($tracks as $i => $track_wrapper): 
    $track = $track_wrapper["item"];
    $title = $track["name"];
    $trackurl = $track["@id"];
    
    // Format duration timestamp P??H??M??S
    $duration = trim(
        preg_replace(["/[A-Z]/", "/^\:00\:0?/"], [":", ""], $track["duration"]),
        ":" 
    );

    $lyrics = str_replace( "\n", "\n    ",
        $track["recordingOf"]["lyrics"]["text"]
    );

    // Fetch commentary, credits and art
    
    $ch = curl_init($trackurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $trackpage = curl_exec($ch);
    unset($ch);

	if($trackpage) {
		phpQuery::newDocumentHTML( $trackpage );
        $trackdata = json_decode(pq("[type='application/ld+json']")->text(), true);
        $trackdata["credits"] = $trackdata["creditText"];
        $trackdata["commentary"] = $trackdata["description"];
        $trackdata["track_art"] = $trackdata["image"];
	}
?>
---
Track: <?php echo $title; ?>

Duration: '<?php echo $duration; ?>'
URLs:
- <?php echo $track["@id"] ?>

<?php if(strlen($lyrics) > 0): ?>
Lyrics: |-
    <?php echo $lyrics ?>

<?php endif?><?php if(isset($trackdata) && strlen($trackdata["commentary"]) > 0): ?>
Commentary: |-
    <i>[NAME]:</i>
    <?php echo preg_replace("/\n\s+/", "\n    ", $trackdata["commentary"]) ?>

<?php endif; if(isset($trackdata) && $trackdata["image"] && $trackdata["image"] !== $album_art): ?>

# Track Art URL: <?php echo $trackdata["image"] ?>

<?php endif; if(isset($trackdata) && strlen($trackdata["creditText"]) > 0): ?>
# Credits:
<?php echo preg_replace("/(^|\n)/", "$1#     ", $trackdata["creditText"]) ?>

<?php endif; endforeach; phpQuery::unloadDocuments(); ?>