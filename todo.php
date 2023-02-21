<!-- ===================================== L4T08 - Compulsory Task 1 ===================================================
    
This task alters the provided todo.php script to keep and flag completed tasks instead of deleting them.

Please see README.md for this task's description and usage.
Please see L4T08_References.txt for the list of references I used in this task.

======================================================================================================================-->

<?php

require 'secrets.php';

// Attempt MySQL server connection.
$mysqli = new mysqli("localhost", DB_UID, DB_PWD, "example_db");
 
// Check connection.
if ($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 
// Print host information.
echo "Connect Successfully. Host info: " . $mysqli->host_info;

// Execute queries based on whether (a) completed/done button or (b) submit button was clicked.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])){
        // (a) Update completed tasks (done button):
        // Store the posted ID in an ID variable.
        $id = $_POST['id'];
        
        //Change the update logic to change the 'completed' field value to true (1) instead of deleting the record.
        $sql = "UPDATE todos SET completed = 1 WHERE id = (?)";
        // Create prepared statement to execute updates.
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $id);
            $stmt->execute();
            // Change the update message from 'record deleted' to record updated'. 
            echo "<br>Records updated successfully.";
        } else {
            echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
        }

    } else {
        // (b) Insert new tasks (submit button):
        $new_title = $_POST['title'];

        $sql = "INSERT INTO todos(title) VALUES (?)";
    
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $new_title);
            $stmt->execute();
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
        }
    }
}

// Create heading and table headers.
?>

<h2>To-do list items</h2>
<table><tbody>
<tr><th>Item</th><th>Added on</th><th>Complete</th></tr>

<?php
// Create sql query string to select all from db, including the newly added 'completed' field. 
// that was added to the database's table.
$sql = "SELECT id, title, created, completed FROM todos";

// Execute query and check success, and check that there are rows returned.
if ($result = $mysqli->query($sql)) {
    if ($result->num_rows > 0) {
        
        // Loop through each row of query result to get row data.
        while ($row = $result->fetch_array()) {
            $current_id = $row['id'];
            $current_completed = $row['completed'];
            
            // If the completed field for this row is 1 (i.e. true), create table rows populated with task data 
            // that shows completed tasks as crossed out(using HTML <del> tags), and remove 'done' button.
            if ($current_id && $current_completed) {
                echo "<tr>";
                    echo "<td><del>" . $row['title'] . "</del></td>";
                    echo "<td><del>" . $row['created'] . "</del></td>";
                echo "</tr>";
            }
            // Else (i.e. if field for this row is false/null), create table rows populated with task data 
            // and 'done' button.
            else {
                echo "<tr>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['created'] . "</td>";
                echo '<td><form method="post" action="todo.php">
                <input type="hidden" name="id" value="'.$row['id'].'">
                <button type="submit">Done</button>
                </form></td>';
            echo "</tr>";
            }
        }

        // Close off table.
        echo "</table>";

        // Free result set to free up memory associated with the result.
        $result->free();
    } else {
        echo "No records matching your query were found.";
    }

} else {
    echo "ERROR: Could not execute $sql. " . $mysqli->error;
}

?>

</tbody></table>

<form method="post" action="todo.php">
   <input type="text" name="title" placeholder="To-do item">
   <button type="submit">Submit</button>
</form>




