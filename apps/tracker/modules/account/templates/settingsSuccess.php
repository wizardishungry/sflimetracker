<h1>Tracker Settings</h1>

<p>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
</p>

<h3>Database Connectivity</h3>

<?php
    $db = sfContext::getInstance()->getDatabaseManager()->getDatabase('propel'); // symfony uses "default" by default!
    $db_params=$db->getParameterHolder()->getAll(); 

    if(!is_null($db_params['password']))
        $db_params['password']='*******';
    $dsn=$db_params['dsn'];
    unset($db_params['dsn']);
?>
<p>
    You should edit <code>config/databases.yml</code> to change the database connection. The active connection is:
    <blockquote>
        <code><?php echo $dsn; ?></code>
    </blockquote>
</p>

<h4>More info</h4>
<ul>
<?php
try {
    foreach($db_params as $k=>$v)
    {
        echo "<li>$k: $v</li>\n";
    }
    $con = Propel::getConnection(); // this is a PDO class

    echo '<li>driver name: ',$con->getAttribute(PDO::ATTR_DRIVER_NAME),'</li>';
    echo '<li>server version: ',$con->getAttribute(PDO::ATTR_SERVER_VERSION),'</li>';
    echo '<li>client version: ',$con->getAttribute(PDO::ATTR_CLIENT_VERSION),'</li>';
    try {
        echo '<li>additional info: ',$con->getAttribute(PDO::ATTR_SERVER_INFO),'</li>';
    }catch(Exception $e){}
    try {
        echo '<li>status: ',$con->getAttribute(PDO::ATTR_CONNECTION_STATUS),'</li>';
    }catch(Exception $e){}
}
catch(Exception $e)
{
    echo "<li>something went wrong while fetching database information: $e </li>";
}
?>
</ul>
