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
    * Test for Bug #8085
    *
    * @link http://pear.php.net/bugs/bug.php?id=8085
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @version $Id: Bug8085.php 77 2007-08-26 09:42:22Z m $
    */

    require_once 'PHPUnit/Framework/TestCase.php';
    require_once 'File/Bittorrent2/Decode.php';

    /**
    * Test for Bug #8085
    *
    * @link http://pear.php.net/bugs/bug.php?id=8085
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @version $Id: Bug8085.php 77 2007-08-26 09:42:22Z m $
    */
    class Tests_Bug8085 extends PHPUnit_Framework_TestCase
    {
        public static $torrent = './bugs/bug-8085/multiple_tracker.torrent';

        public function testAnnounceList()
        {
            $Decode = new File_Bittorrent2_Decode;
            $info = $Decode->decodeFile(self::$torrent);
            $expected = array (
                array (
                    'http://tracker.gotwoot.net:6968/announce',
                ),
                array (
                    'http://www.point-blank.cc:6969/announce',
                    'http://www.point-blank.cc:7000/announce',
                    'http://www.point-blank.cc:7001/announce',
                ),
            );
            $this->assertEquals($expected, $info['announce_list']);
            unset($Decode);
        }
    }

?>