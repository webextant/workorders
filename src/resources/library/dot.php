<?php

/*
	Author: Raymond Brady (@thewizster)
	Created: 1450560977 GMT: Sat, 19 Dec 2015 21:36:17
	Description: Render HTML forms based on XML. For use in production applications.
*/

    class Dot {
        public function getForm($id)
        {
            // returns form data for use with other functions in this class
            $formObject;
            require "./config/db.php";
            $conn = new PDO($dsn, $user_name, $pass_word);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $sql = "SELECT * FROM FormDefinitions WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array('id' => $id));
                $formObject = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e)
            {
                echo $sql . "<br>" . $e->getMessage();
            }
            $conn = null;
            return $formObject;
        }
        public function renderUserFormsList($createActionString)
        {
            // renders all saved forms in a boostrap table.
            // Used for creating new workorders.
            require "./config/db.php";
            $conn = new PDO($dsn, $user_name, $pass_word);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $sql = "SELECT * FROM FormDefinitions WHERE Available = 1 ORDER by FormName ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $form = $stmt->fetchAll();

                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th></th>";
                echo "<th>Name</th>";
                echo "<th>Description</th>";
                echo "<th></th>";
                echo "<th></th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($form as $row) {
                    echo "<tr>"; // START TABLE ROW
                        echo "<td></td>";
                        echo "<td>" . $row["FormName"] . "</td>";
                        echo "<td>" . $row["Description"] . "</td>";
                        $formid= $row["id"];
                        $workformid = "workform" . $formid;
                        echo "<td><form id='$workformid' method='post' action='$createActionString'><input name='id' type='hidden' value='$formid'><a href='' onclick=\"document.getElementById('$workformid').submit();return false;\" class='btn btn-primary'>Create Workorder</a></form></td>";
                    echo "</tr>"; // END TABLE ROW
                }
                echo "</tbody>";
                echo "</table>";
            }
            catch(PDOException $e)
            {
                echo $sql . "<br>" . $e->getMessage();
            }
            $conn = null;
        }
    }

?>