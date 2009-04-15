function generateSlug(string) {
  bad =
   ['À','à','Á','á','Â','â','Ã','ã','Ä','ä','Å','å','Ă','ă','Ą','ą',
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
    '”','“','‘','’',"'","\n","\r",'_'];
  good =
   ['A','a','A','a','A','a','A','a','Ae','ae','A','a','A','a','A','a',
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
    '','','','','','','','-'];

  string = str_replace(bad, good, string);
  string = utf8_decode(string);
  string = string.stripTags();
  string = string.toLowerCase();
  string = string.replace(/\W/g, ' ');
  string = string.replace(/\s+/g, '-');
  string = string.replace(/^-/, '').replace(/-$/, '');

  return string;
}

function str_replace(search, replace, subject) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    var s = subject;
    var ra = r instanceof Array, sa = s instanceof Array;
    var f = [].concat(search);
    var r = [].concat(replace);
    var i = (s = [].concat(s)).length;
    var j = 0;

    while (j = 0, i--) {
        if (s[i]) {
            while (s[i] = (s[i]+'').split(f[j]).join(ra ? r[j] || "" : r[0]), ++j in f){};
        }
    }

    return sa ? s : s[0];
}

function utf8_decode ( str_data ) {
    // Converts a UTF-8 encoded string to ISO-8859-1
    //
    // version: 903.3016
    // discuss at: http://phpjs.org/functions/utf8_decode
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Norman "zEh" Fuchs
    // +   bugfixed by: hitwork
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brettz9.blogspot.com)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: utf8_decode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'
    var tmp_arr = [], i = 0, ac = 0, c1 = 0, c2 = 0, c3 = 0;

    str_data += '';

    while ( i < str_data.length ) {
        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if ((c1 > 191) && (c1 < 224)) {
            c2 = str_data.charCodeAt(i+1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i+1);
            c3 = str_data.charCodeAt(i+2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    }

    return tmp_arr.join('');
}


function watchForSlug(source, preview) {
  setSlugOrEmpty(preview, source.value);
  source.observe('keyup', function(change) {
    setSlugOrEmpty(preview, this.value);
  });
}

function setSlugOrEmpty(preview, value) {
  if(value == "") {
    preview.innerHTML = "(empty)";
  } else {
    preview.innerHTML = generateSlug(value);
  }
}