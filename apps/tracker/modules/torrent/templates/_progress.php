<?php
    $suffix=$done?'ed':'ing';
    $http_code=$info['http_code'];
    switch($http_code)
    {
        case 0:
            $verb='connect';
            break;
        case 200:
            $verb='download';
            break;
        case 404:
            $verb='not found';
            break;
        case($http_code>=300 && $http_code<=400):
            $verb='redirect';
            break;
        case($http_code>=400 && $http_code<=500):
            $verb='error'.$http_code;
            break;
        default:
            $verb='wtf'.$http_code;
    }
    if($info['download_content_length'])
        $percent=$info['size_download']/$info['download_content_length'] *100.0;
    else
    $percent=0;
?>
<html>
    <head>
        <title><?php echo $verb,$suffix ?></title>
    </head>
    <body>
        <?php echo $verb,$suffix,' ',$info['url'],"($percent%)"; ?>
        <div style="background: red; <?php
            echo "width: $percent%; ";
        ?>">&nbsp;
        </div>
        <!--
        <pre>
            <?php print_r($info); ?>
            <?php echo "calls $calls ; skipped $skipped"; ?>
        </pre> -->
        <?php echo '<!--',str_repeat(' ',1024),'-->'; ?>
    </body>
</html>
