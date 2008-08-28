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
    * @author Robin H. Johnson <robbat2@gentoo.org>
    * @version $Id: FileBittorrent2.php 77 2007-08-26 09:42:22Z m $
    */

    require_once 'PHPUnit/Framework/TestCase.php';
    require_once 'File/Bittorrent2/Decode.php';

    /**
    * Test for File_Bittorrent2
    *
    * @package File_Bittorrent2
    * @subpackage Test
    * @category File
    * @author Markus Tacker <m@tacker.org>
    * @author Robin H. Johnson <robbat2@gentoo.org>
    * @version $Id: FileBittorrent2.php 77 2007-08-26 09:42:22Z m $
    */
    class Tests_FileBittorrent2 extends PHPUnit_Framework_TestCase
    {
        public static $torrent = './install-x86-universal-2005.0.iso.torrent';

        public function testInfoHash()
        {
            $File_Bittorrent2_Decode = new File_Bittorrent2_Decode;
            $File_Bittorrent2_Decode->decodeFile(self::$torrent);
            exec('torrentinfo-console ' . escapeshellarg(self::$torrent), $bt);
            $this->assertEquals($File_Bittorrent2_Decode->getInfoHash(), substr($bt[3], strpos($bt[3], ':') + 2));
        }

        public function testDecode()
        {
            $test_data = array(
                '0:0:'                     => false, // data past end of first correct bencoded string
                'ie'                       => false, // empty integer
                'i341foo382e'              => false, // malformed integer
                'i4e'                      => 4,
                'i0e'                      => 0,
                'i123456789e'              => 123456789,
                'i-10e'                    => -10,
                'i-0e'                     => false, // negative zero integer
                'i123'                     => false, // unterminated integer
                ''                         => false, // empty string
                'i6easd'                   => false, // integer with trailing garbage
                '35208734823ljdahflajhdf'  => false, // garbage looking vaguely like a string, with large count
                '2:abfdjslhfld'            => false, // string with trailing garbage
                '0:'                       => '',
                '3:abc'                    => 'abc',
                '10:1234567890'            => '1234567890',
                '02:xy'                    => false, // string with extra leading zero in count
                'l'                        => false, // unclosed empty list
                'le'                       => array(),
                'leanfdldjfh'              => false, // empty list with trailing garbage
                'l0:0:0:e'                 => array('', '', ''),
                'relwjhrlewjh'             => false, // complete garbage
                'li1ei2ei3ee'              => array( 1, 2, 3 ),
                'l3:asd2:xye'              => array( 'asd', 'xy' ),
                'll5:Alice3:Bobeli2ei3eee' => array ( array( 'Alice', 'Bob' ), array( 2, 3 ) ),
                'd'                        => false, // unclosed empty dict
                'defoobar'                 => false, // empty dict with trailing garbage
                'de'                       => array(),
                'd1:a0:e'                  => array('a'=>''),
                'd3:agei25e4:eyes4:bluee'  => array('age' => 25, 'eyes' => 'blue' ),
                'd8:spam.mp3d6:author5:Alice6:lengthi100000eee' => array('spam.mp3' => array( 'author' => 'Alice', 'length' => 100000 )),
                'd3:fooe'                  => false, // dict with odd number of elements
                'di1e0:e'                  => false, // dict with integer key
                'd1:b0:1:a0:e'             => false, // missorted keys
                'd1:a0:1:a0:e'             => false, // duplicate keys
                'i03e'                     => false, // integer with leading zero
                'l01:ae'                   => false, // list with string with leading zero in count
                '9999:x'                   => false, // string shorter than count
                'l0:'                      => false, // unclosed list with content
                'd0:0:'                    => false, // unclosed dict with content
                'd0:'                      => false, // unclosed dict with odd number of elements
                '00:'                      => false, // zero-length string with extra leading zero in count
                'l-3:e'                    => false, // list with negative-length string
                'i-03e'                    => false, // negative integer with leading zero
                'di0e0:e'                  => false, // dictionary with integer key
                'd8:announceldi0e0:eee'    => false, // nested dictionary with integer key
                'd8:announcedi0e0:e18:azureus_propertiesi0ee' => false, // nested dictionary with integer key #2
            );
            // Thanks to IsoHunt.com for the last 3 testcases of bad data seen in their system.

            $File_Bittorrent2_Decode = new File_Bittorrent2_Decode;
            ini_set('mbstring.internal_encoding','ASCII');
            foreach($test_data as $ti => $to) {
				if ($to === false) {
					try {
						$File_Bittorrent2_Decode->decode($ti);
						$this->fail('File_Bittorrent2 successfully decoded invalid data.');
					} catch (File_Bittorrent2_Exception $E) {
						if ($E->getCode() != File_Bittorrent2_Exception::decode) throw $E;
					}
				} else {
					$this->assertEquals($to, $File_Bittorrent2_Decode->decode($ti));
				}
            }
        }
    }

?>
