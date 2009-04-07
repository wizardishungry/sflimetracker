<?php
/**
This continually evolving script can be dropped into a LimeTracker install and used to diagnose problems.
**/

if(isset($_GET['phpinfo'])) { phpinfo(); exit(); }

function assert_callcack($file, $line, $message) {
  echo "assert failed: <code>$message</code><br>\n";
}

assert_options(ASSERT_ACTIVE,    true);
assert_options(ASSERT_BAIL,     false);
assert_options(ASSERT_WARNING,     false);
assert_options(ASSERT_CALLBACK, 'assert_callcack');
error_reporting(E_ALL & E_STRICT);

assert("version_compare(PHP_VERSION, '5.1.0') === 1");

$path=$_SERVER['SCRIPT_FILENAME'];
if(!$path)
    $path=__FILE__;

$root=dirname($path);
$paths=Array('cache','data','data/tracker.db','log','uploads');
foreach($paths as &$path)
{
  $path="$root/$path";
  assert("file_exists('$path')");
  assert("is_writable('$path')");
}

assert('class_exists("PDO",false)');
assert('function_exists("apache_setenv")');
assert('function_exists("hash_init)');

assert('is_bool("If this is the only text you see, something good happened or we didnt look hard enough.")');
?>
<a href="?phpinfo=sure">Phpinfo</a>?
