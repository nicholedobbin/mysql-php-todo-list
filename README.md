# **To Do List with MySQL and PHP**

## **IFS L4T08 - MySQL in PHP**

## **Description**
This is a to-do list created using MySQL and PHP.

The project consists of two compulsory tasks:
#### **Compulsory Task 1 (CT1)**
This task required altering a provided [todo.php](todo.php) script to keep and flag completed tasks instead of deleting them.

*Modifications made to 'example_db' database and [todo.php](todo.php) script:*
* A new field called 'completed' with a boolean type was added to the 'todos' table, to keep track of whether a task is complete.
* The update logic was modified to remove the delete update and replace it with an update that marks the task as complete (by changing that record's 'completed' field value to true when the 'done' button is clicked).
* The retrieval logic was modified to show completed tasks as strikethrough (crossed out) by using HTML <del> tags.

#### **Compulsory Task 2 (CT2)**
This task required modifying the script in CT1 to hide completed tasks by default and allow the user to see them at will. 
The modified script is stored in a php file called [todo2.php](todo2.php), so that both CT1 and CT2 modifications can be seen separately.

*Modifications made to [todo2.php](todo2.php) script:*
* The update logic was modified to store posted data for the added "Show completed" and "Hide completed" buttons.
* The retrieval logic was modified by adding a button below the tasks labelled “Show completed” that reloads the page and displays:
    * all tasks (completed and uncompleted).
    * a “Hide completed” button that does the reverse (i.e. displays the original, uncompleted tasks table with the "Show completed" button).

<hr>

## **Installation and Setup**
**Note:**
This program uses a file called 'secrets.php' (see the code: 'require secrets.php' at the top of todo.php and todo2.php) to protect sensitive data (in this case the database's username and password, for connecting to the database). You will need to create your own secret file with that data, or hard code it into the todo files by replacing DB_UID and DB_PWD with the username and password you created. 

#### **Database Setup**
1. You need to set up your own MySQL server database for this project. See [dev.mysql.com](https://dev.mysql.com/doc/mysql-getting-started/en/) for instructions on how to do this.
2. Create a new database and user with the required table and user data. You can use the following example in a MySQL command-line client for creating the table: `CREATE TABLE todos (id INT AUTO_INCREMENT, title VARCHAR(255) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id));`
3. Start your MySQL server.

#### **Project Setup**
1. Clone the repo and open with your preferred IDE (e.g. [VSCode](https://code.visualstudio.com/docs/setup/setup-overview)).
2. In the command line, create a localhost mini-server with the command: `php -S localhost:8080`.
3. To run the script in your browser, add the PHP filename to the end of the localhost url, e.g. http://localhost:8080/todo.php

<hr>

## **How To Use**
1. Once in the browser, enter a to-do item in the form and click submit.
2. Click 'done' to mark the item as complete.
3. Click 'show completed' (todo2.php) to show the list of completed tasks.

<hr>

## **Credits and References**
Made by [Nichole Dobbin](https://github.com/nicholedobbin), for my [HyperionDev](https://www.hyperiondev.com/) course.
Please see [L4T08_References.txt ](/L4T08_References.txt) for the list of references I used in this task.
