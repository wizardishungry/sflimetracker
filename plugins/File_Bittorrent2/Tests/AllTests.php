<?php

// +----------------------------------------------------------------------+
// | Decode and Encode data in Bittorrent format                          |
// +----------------------------------------------------------------------+
// | Copyright (C) 2004-2006 Markus Tacker <m@tacker.org>                 |
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
    * Test for File_Bittorrent2
    *
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @version $Id: AllTests.php 82 2008-06-12 09:47:04Z m $
    */

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once 'Tests/FileBittorrent2.php';
    require_once 'Tests/Bug7406.php';
    require_once 'Tests/Bug8085.php';
    require_once 'Tests/Bug14013.php';

    /**
    * Test for File_Bittorrent2
    *
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @version $Id: AllTests.php 82 2008-06-12 09:47:04Z m $
    */
    class Tests_AllTests {

        public static function suite() {
            $suite = new PHPUnit_Framework_TestSuite();

            $suite->addTestSuite('Tests_FileBittorrent2');
            $suite->addTestSuite('Tests_Bug7406');
            $suite->addTestSuite('Tests_Bug8085');
            $suite->addTestSuite('Tests_Bug14013');

            return $suite;
        }
    }

?>