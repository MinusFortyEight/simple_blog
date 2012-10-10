<?php

    include_once 'inc/db.inc.php';
    include_once 'inc/functions.inc.php';

    // open a database connection
    $db = new PDO(DB_INFO, DB_USER, DB_PASS);

    // determine if an entry ID has been passed in the URL
    $id = (isset($_GET['id'])) ? (int) $_GET['id'] : NULL;

    // load the entries
    $e = retrieveEntries($db, $id);
    //var_dump($e);

    $fulldisp = 0;
    // get full display flag and remove it from array
    if(is_array($e))
    {
        $fulldisp = array_pop($e);
    }

    // sanitize the entry data
    $e = sanitizeData($e);

?>

<!DOCTYPE html
        PUBLIC "-//W3C/DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content Type"
          content="text/html:charset=utf-8" />
    <link rel="stylesheet" href="css/default.css" type="text/css" />
    <title> Simple Blog </title>
</head>

<body>

    <h1> Simple Blog Application </h1>

    <!-- display entries from the database -->
    <div id="entries">

        <!-- format entries from database -->
        <?php

            // if the full display flag is 1 (meaning an entry has just been posted or selected from list of entries), show the entry
            if($fulldisp == 1)
            {
        ?>
                <h2> <?php echo $e['title'] ?> </h2>
                <p> <?php echo $e['entry'] ?> </p>
                <p class="backlink">
                    <a href="./">Back to Latest Entries</a>
                </p>
        <?php
            }

            // if the full display flag is 0, format linked entry titles
            else
            {
                if(is_array($e))
                {
                // loop through each entry
                    foreach($e as $entry)
                    {
        ?>
                        <p>

                            <a href="?id=<?php echo $entry['id']; ?>">
                            <?php echo $entry['title']; ?>
                            </a>

                        </p>
        <?php
                    }
                }
            }

        ?>

        <p class="backlink">
            <a href="admin.php"> Post a New Entry </a>
        </p>

    </div>

</body>

</html>