<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrew.Harderson
 * Date: 10/9/12
 * Time: 10:17 PM
 * To change this template use File | Settings | File Templates.
 */

    // get entries from database
    function retrieveEntries($db, $id=NULL)
    {
        // if entry ID is supplied, load associated entry. This is when an entry was just submitted
        if(isset($id))
        {
            // load it, bitch!
            $sql = 'select title, entry
                    from entries
                    where id=?
                    limit 1';
            $stmt = $db->prepare($sql);
            $stmt->execute(array($_GET['id']));

            // save returned entry array
            $e = $stmt->fetch();

            // set full display flag for a single entry
            $fulldisp = 1;
        }
        // load all entry titles as a new entry has not been submitted
        else
        {
            $sql = 'select id, title, entry
                    from entries
                    order by created desc';

            // loop through returned entries (results) and store them as an array

            foreach($db->query($sql) as $row)
            {
                $e[] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'entry' => $row['entry']
                );
            }

            /*
             *  set the full display flag for multiple entries.
             *  If no entry ID is supplied, flag is set to 0 so
             *  presentation layer knows it is NOT for full display
             */
            $fulldisp = 0;

            /*
             * if no entries were returned, display a default message and set full display flag
             * to display a single entry
             */
            if(!is_array($e))
            {
                $fulldisp = 1;
                $e = array(
                    'title' => 'No Entries Yet!',
                    'entry' => '<a href="admin.php">Post an entry, asshole!</a>'
                );
            }
        }

        // add full display flag to end of the array
        array_push($e, $fulldisp);

        return $e;
    }

    // escape the output to remove all HTML tags
    function sanitizeData($data)
    {
        // if $data is not an array, run strip_tags()
        if(!is_array($data))
        {
            // remove all tags except <a> tags
            return strip_tags($data, "<a>");
        }
        // if $data is an array, process each element
        else
        {
            // call sanitizeData recursively to look through each index in the array
            return array_map('sanitizeData', $data);
        }
    }

?>