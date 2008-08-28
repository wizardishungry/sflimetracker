<?php

// +----------------------------------------------------------------------+
// | Decode and Encode data in Bittorrent format                          |
// +----------------------------------------------------------------------+
// | Copyright (C) 2004-2005 Markus Tacker <m@tacker.org>                 |
// +----------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or        |
// | modify it under the terms of the GNU Lesser General Public           |
// | License as published by the Free Software Foundation; either         |
// | version 2.1 of the License, or (at your option) any later version.   |
// |                                                                      |
// | This library is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU    |
// | Lesser General Public License for more details.                      |
// |                                                                      |
// | You should have received a copy of the GNU Lesser General Public     |
// | License along with this library; if not, write to the                |
// | Free Software Foundation, Inc.                                       |
// | 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA               |
// +----------------------------------------------------------------------+

/**
* Test for Bug #14013
*
* @link http://pear.php.net/bugs/bug.php?id=14013
* @package File_Bittorrent2
* @subpackage Test
* @category File
* @author Markus Tacker <m@tacker.org>
* @version $Id: Bug14013.php 82 2008-06-12 09:47:04Z m $
*/

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'File/Bittorrent2/MakeTorrent.php';
require_once 'File/Bittorrent2/Decode.php';

/**
* Test for Bug #14013
*
* @link http://pear.php.net/bugs/bug.php?id=14013
* @package File_Bittorrent2
* @subpackage Test
* @category File
* @author Markus Tacker <m@tacker.org>
* @version $Id: Bug14013.php 82 2008-06-12 09:47:04Z m $
*/
class Tests_Bug14013 extends PHPUnit_Framework_TestCase
{
	public function testMakePrivateTorrent()
	{
		$Torrent = new File_Bittorrent2_MakeTorrent( __FILE__ );
		$Torrent->setAnnounce( 'http://example.com/' );
		$Torrent->setIsPrivate( true );
		$tfile = tempnam('./', __CLASS__);
		$bemetainfo = $Torrent->buildTorrent();
		file_put_contents( $tfile, $bemetainfo );

		$Decode = new File_Bittorrent2_Decode();
		$Decode->decodeFile( $tfile );
		$metainfo = $Decode->decode( $bemetainfo );
		unlink( $tfile );
		$this->assertTrue( $Decode->isPrivate() );
		$this->assertTrue( $metainfo['info']['private'] === 1 );
	}
}