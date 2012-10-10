<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrew.Harderson
 * Date: 10/9/12
 * Time: 9:42 PM
 * To change this template use File | Settings | File Templates.
 */

if($_SERVER['REQUEST_METHOD']=='POST'
    && $_POST['submit']=='save entry'
    && !empty($_POST['title'])
    && !empty($_POST['entry']))
    {
        // database credentials included, connection created
        include_once 'db.inc.php';
        $db = new PDO(DB_INFO, DB_USER, DB_PASS);

        // save entry to database
        $sql = 'insert into entries (title, entry) values (?, ?)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($_POST['title'],$_POST['entry']));
        $stmt->closeCursor();

        // get ID of entry just saved
        $id_obj = $db->query("select last_insert_id()");
        $id = $id_obj->fetch();
        $id_obj->closeCursor();

        // send user to new entry
        header('Location: ../?id=' . $id[0]);
    }
// if both conditions aren't met (post method was 'save entry' click AND both title and entry fields were filled in), send user back to main page
else
    {
        header('Location: ../');
        exit;
    }

?>