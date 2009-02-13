<?php
/*
 * This file is part of the sfPropelActAsSluggableBehavior package.
 * 
 * (c) 2006-2007 Guillermo Rauch (http://devthought.com)
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class sfPropelActAsSluggableBehaviorUtils
{
  public static function stripText($text, $separator = '-')
  {
  	$bad = array(
    'À','à','Á','á','Â','â','Ã','ã','Ä','ä','Å','å','Ă','ă','Ą','ą',
    'Ć','ć','Č','č','Ç','ç',
    'Ď','ď','Đ','đ',
    'È','è','É','é','Ê','ê','Ë','ë','Ě','ě','Ę','ę',
    'Ğ','ğ',
    'Ì','ì','Í','í','Î','î','Ï','ï',
    'Ĺ','ĺ','Ľ','ľ','Ł','ł',
    'Ñ','ñ','Ň','ň','Ń','ń',
    'Ò','ò','Ó','ó','Ô','ô','Õ','õ','Ö','ö','Ø','ø','ő',
    'Ř','ř','Ŕ','ŕ',
    'Š','š','Ş','ş','Ś','ś',
    'Ť','ť','Ť','ť','Ţ','ţ',
    'Ù','ù','Ú','ú','Û','û','Ü','ü','Ů','ů',
    'Ÿ','ÿ','ý','Ý',
    'Ž','ž','Ź','ź','Ż','ż',
    'Þ','þ','Ð','ð','ß','Œ','œ','Æ','æ','µ',
    '”','“','‘','’',"'","\n","\r",'_');

    $good = array(
    'A','a','A','a','A','a','A','a','Ae','ae','A','a','A','a','A','a',
    'C','c','C','c','C','c',
    'D','d','D','d',
    'E','e','E','e','E','e','E','e','E','e','E','e',
    'G','g',
    'I','i','I','i','I','i','I','i',
    'L','l','L','l','L','l',
    'N','n','N','n','N','n',
    'O','o','O','o','O','o','O','o','Oe','oe','O','o','o',
    'R','r','R','r',
    'S','s','S','s','S','s',
    'T','t','T','t','T','t',
    'U','u','U','u','U','u','Ue','ue','U','u',
    'Y','y','Y','y',
    'Z','z','Z','z','Z','z',
    'TH','th','DH','dh','ss','OE','oe','AE','ae','u',
    '','','','','','','','-');

    // convert special characters
    $text = str_replace($bad, $good, $text);
  	
    // convert special characters
    $text = utf8_decode($text);
    $text = htmlentities($text);
    $text = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $text);
    $text = html_entity_decode($text);
    
    $text = strtolower($text);

    // strip all non word chars
    $text = preg_replace('/\W/', ' ', $text);

    // replace all white space sections with a separator
    $text = preg_replace('/\ +/', $separator, $text);

    // trim separators
    $text = trim($text, $separator);
    //$text = preg_replace('/\-$/', '', $text);
    //$text = preg_replace('/^\-/', '', $text);
        
    return $text;
  }

  
}

?>