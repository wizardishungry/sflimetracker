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
* Example usage of File_Bittorrent2
*
* @author Markus Tacker <m@tacker.org>
* @version $Id: example.php 77 2007-08-26 09:42:22Z m $
*/

/**
* Include class
*/
require_once 'File/Bittorrent2/Encode.php';
require_once 'File/Bittorrent2/Decode.php';

$File_Bittorrent2_Decode = new File_Bittorrent2_Decode;
$File_Bittorrent2_Encode = new File_Bittorrent2_Encode;

// Encoding vars
echo "Encoding integers\n";
$encodedInt = $File_Bittorrent2_Encode->encode(10);
var_dump($encodedInt);
var_dump($File_Bittorrent2_Decode->decode($encodedInt));

echo "Encoding strings\n";
$encodedStr = $File_Bittorrent2_Encode->encode('This is a string.');
var_dump($encodedStr);
var_dump($File_Bittorrent2_Decode->decode($encodedStr));

echo "Encoding arrays as lists\n";
$encodedList = $File_Bittorrent2_Encode->encode(array('Banana', 'Apple', 'Cherry'));
var_dump($encodedList);
var_dump($File_Bittorrent2_Decode->decode($encodedList));

echo "Encoding arrays as dictionaries\n";
$encodedDict = $File_Bittorrent2_Encode->encode(array('fruits' => array('Banana', 'Apple', 'Cherry','subarray' => array(1,2,3)), 'ints' => array(1,2,3), 'count' => 3));
var_dump($encodedDict);
print_r($File_Bittorrent2_Decode->decode($encodedDict));

// Decode a file
print_r($File_Bittorrent2_Decode->decodeFile('install-x86-universal-2005.0.iso.torrent'));

/* Output of decode

Array
(
    [count] => 3
    [fruits] => Array
        (
            [0] => Banana
            [1] => Apple
            [2] => Cherry
            [subarray] => Array
                (
                    [0] => 1
                    [1] => 2
                    [2] => 3
                )

        )

    [ints] => Array
        (
            [0] => 1
            [1] => 2
            [2] => 3
        )

)
Array
(
    [name] => install-x86-universal-2005.0.iso
    [filename] => install-x86-universal-2005.0.iso.torrent
    [comment] =>
    [date] => 1111915968
    [created_by] =>
    [files] => Array
        (
            [0] => Array
                (
                    [filename] => install-x86-universal-2005.0.iso
                    [size] => 712460288
                )

        )

    [size] => 712460288
    [announce] => http://titmouse.gentoo.org:6969/announce
    [announce_list] => Array
        (
        )

    [info_hash] => f94b8d2d38632a6589d8121106059989f290b569
)

*/

?>