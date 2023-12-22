<?php
require_once("phpQuery/phpQuery.php");
if(!isset($trackurl)) $trackurl = $_GET["url"];
phpQuery::newDocumentHTML( file_get_contents($trackurl) );
$trackdata = [
	"commentary" =>  trim( pq(".tralbum-about")->text() ),
	"track_art" => trim( pq(".popupImage img")->attr("src") ),
	"credits" => preg_replace("/\n\s+/", "\n", trim( pq(".tralbum-credits")->text() ) )
];