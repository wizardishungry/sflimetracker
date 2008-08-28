<?php

// +----------------------------------------------------------------------+
// | MakeTorrent and Encode data in Bittorrent format                          |
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
    * Test for Bug #7406
    *
    * @link http://pear.php.net/bugs/bug.php?id=7406
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @version $Id: Bug7406.php 77 2007-08-26 09:42:22Z m $
    */

    require_once 'PHPUnit/Framework/TestCase.php';
    require_once 'File/Bittorrent2/MakeTorrent.php';
    require_once 'File/Bittorrent2/Decode.php';

    /**
    * Test for Bug #7406
    *
    * @link http://pear.php.net/bugs/bug.php?id=7406
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @version $Id: Bug7406.php 77 2007-08-26 09:42:22Z m $
    */
    class Tests_Bug7406 extends PHPUnit_Framework_TestCase
    {
        public static $torrent = './bugs/bug-7406/TestDir';

        public function testAnnounceList()
        {
            $MakeTorrent = new File_Bittorrent2_MakeTorrent(self::$torrent);
            // Set the announce URL
            $MakeTorrent->setAnnounce('http://www.example.org');
            // Set the comment
            $MakeTorrent->setComment('Hello World!');
            // Set the piece length (in KB)
            $MakeTorrent->setPieceLength(256);
            // Build the torrent
            $metainfo = $MakeTorrent->buildTorrent();

            $Decode = new File_Bittorrent2_Decode();
            $info = $Decode->decode($metainfo);
            $this->assertEquals(count($info['info']['files']), 3);
            $files = array();
            foreach ($info['info']['files'] as $k => $v) {
                $files[] = $v['path'][0];
            }
            sort($files);
            $expected = array (
                '1.txt',
                '2.txt',
                '3.txt',
            );
            $this->assertEquals($expected, $files);
        }
    }

?>