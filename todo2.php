<!-- ===================================== L4T08 - Compulsory Task 2 ===================================================
    
This task modifies the script in Compulsory Task 1 to hide completed tasks by default and 
allow the user to see them at will. 

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

// Execute queries based on whether (a)'done' button, (b)'Show Completed' button, (c)'Hide Completed' 
//  button or (d)'submit' button was clicked.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // (a) Update completed tasks with "done" button.
    if (isset($_POST['id'])){
        $id = $_POST['id'];
        $sql = "UPDATE todos SET completed = 1 WHERE id = (?)";
        
        // Create prepared statement to execute updates.
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $id);
            $stmt->execute();
            echo "<br>Records updated successfully.";
        } else {
            echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
        }

    }  
    // (b) Set $show_completed_btn to store posted "Show Completed" button value. 
    else if (isset($_POST['show_completed_btn'])) {
        $show_completed_btn = $_POST['show_completed_btn'];
    } 
    // (c) Set $hide_completed_btn to store posted "Hide Completed" button value. 
    else if (isset($_POST['hide_completed_btn'])) {
        $hide_completed_btn = $_POST['hide_completed_btn'];
    }
    // (d) Insert new tasks with 'submit' button.
    else {
        $new_title = $_POST['title'];
        $sql = "INSERT INTO todos(title) VALUES (?)";
        
        // Create prepared statement to insert new data.
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $new_title);
            $stmt->execute();
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
        }
    }
}

// Create HTML heading and table headers.
?>
<h2>To-do list items</h2>
<table><tbody>
<tr><th>Item</th><th>Added on</th><th>Complete</th></tr>

<?php

// If 'Show completed' button is clicked:
if (isset($show_completed_btn)) {           
    // Create SQL query string to select all from todos.
    $sql = "SELECT * FROM todos";

    // Execute query and check success, and check that there are rows returned.
    if ($result = $mysqli->query($sql)) {
        if ($result->num_rows > 0) {
            // Loop through each row of query result to get row data.
            while ($row = $result->fetch_array()) {
                $current_id = $row['id'];
                $current_completed = $row['completed'];
                
                // If the completed field for this row is not 1 (i.e. false), create table rows populated with 
                // tasks that are uncompleted and a 'done' button.
                if ($current_id && !$current_completed) {
                    echo "<tr>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['created'] . "</td>";
                    echo '<td><form method="post" action="todo2.php">
                            <input type="hidden" name="id" value="'.$row['id'].'">
                            <button type="submit">Done</button>
                        </form></td>';
                    echo "</tr>";
                }

                // If the completed field for this row is 1 (i.e. true), create table rows populated with 
                // tasks that are completed (indicated by strikethrough with <del> tag), and remove the 'done' button.
                if ($current_id && $current_completed) {
                    echo "<tr>";
                        echo "<td><del>" . $row['title'] . "</del></td>";
                        echo "<td><del>" . $row['created'] . "</del></td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "No records matching your query were found.";
        }
        // Close table.
        echo "</table>";

        // Add 'Hide completed' button below table.
        echo '<td><form method="post" action="todo2.php">
                <input type="hidden" name="hide_completed_btn">
                <button type="submit">Hide Completed</button>
            </form></td>';

        // Free result set to free up memory associated with the result.
        $result->free();
    } else {
        echo "ERROR: Could not execute $sql. " . $mysqli->error;
    }
}
else if (isset($hide_completed_btn)){
    // Create sql query string with a WHERE condition that only gets uncompleted tasks.
    $sql = "SELECT id, title, created, completed FROM todos WHERE completed IS NULL";

    // Execute query and check success, and check that there are rows returned.
    if ($result = $mysqli->query($sql)) {
        if ($result->num_rows > 0) {
            // Loop through each row of query result to get row data.
            while ($row = $result->fetch_array()) {
                $current_id = $row['id'];
                $current_completed = $row['completed'];   

                // If the completed field for this row is not 1 (i.e. false), create table rows populated with 
                // tasks that are uncompleted and a 'done' button.
                if ($current_id && !$current_completed) {
                    echo "<tr>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['created'] . "</td>";
                    echo '<td><form method="post" action="todo2.php">
                            <input type="hidden" name="id" value="'.$row['id'].'">
                            <button type="submit">Done</button>
                        </form></td>';
                    echo "</tr>";
                }
            }
            // Close table.
            echo "</table>";

            // Add 'Show completed' button below table.
            echo '<td><form method="post" action="todo2.php">
                <input type="hidden" name="show_completed_btn">
                <button type="submit">Show Completed</button>
            </form></td>';

            // Free result set to free up memory associated with the result.
            $result->free();
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        echo "ERROR: Could not execute $sql. " . $mysqli->error;
    }

} 
else {
    // Repeat same process as "else if (isset($hide_completed_btn))" process. 
    // This ensures the only the uncompleted tasks are shown when 'Show completed' or 'Hide completed'
    // buttons have not been clicked (i.e. on page load or when adding new tasks).
    $sql = "SELECT id, title, created, completed FROM todos WHERE completed IS NULL";

    if ($result = $mysqli->query($sql)) {
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_array()) {
                $current_id = $row['id'];
                $current_completed = $row['completed'];   

                if ($current_id && !$current_completed) {
                    echo "<tr>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['created'] . "</td>";
                    echo '<td><form method="post" action="todo2.php">
                            <input type="hidden" name="id" value="'.$row['id'].'">
                            <button type="submit">Done</button>
                        </form></td>';
                    echo "</tr>";
                }
            }
            echo "</table>";
            
            echo '<td><form method="post" action="todo2.php">
                <input type="hidden" name="show_completed_btn">
                <button type="submit">Show Completed</button>
            </form></td>';

            $result->free();
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        echo "ERROR: Could not execute $sql. " . $mysqli->error;
    }
} 
?>

</tbody></table>

<form method="post" action="todo2.php">
   <input type="text" name="title" placeholder="To-do item">
   <button type="submit">Submit</button>
</form>




