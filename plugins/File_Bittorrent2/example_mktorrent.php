<?php

error_reporting(E_ALL);

require_once 'File/Bittorrent2/MakeTorrent.php';

$MakeTorrent = new File_Bittorrent2_MakeTorrent('example.php');

// Set the announce URL
$MakeTorrent->setAnnounce('http://www.example.org');
// Set the comment
$MakeTorrent->setComment('Hello World!');
// Set the piece length (in KB)
$MakeTorrent->setPieceLength(256);
// Build the torrent
$metainfo = $MakeTorrent->buildTorrent();
// Then put this into a file, instead of echoing it normally...
echo $metainfo;

?>