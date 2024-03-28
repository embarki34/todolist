<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// addin g taskes to data base 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        if ($conn->query($sql) === TRUE) {
            echo "Task added successfully!";
        } else {
            echo "Error adding task: " . $conn->error;
        }
    }
}
//handel delet code 
if (isset($_POST['delete'])) {
    $taskId = $_POST['delete'];
    $sql = "DELETE FROM tasks WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        echo "Task deleted successfully!";
    } else {
        echo "Error adding task: " . $conn->error;
    }
}

//handel the complite code
if (isset($_POST['complete'])) {
    $taskId = $_POST['complete'];
    $sql = "UPDATE tasks SET completed = 1 WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        echo "Task marked as complete!";
    } else {
        echo "Error updating task: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>todolist</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        
        h1 {
            text-align: center;
            color: #333;
        }
        
        form {
            max-width: 400px;
            margin: 0 auto;
            display: flex;
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        ul {
            list-style-type: none;
            padding: 0;
            max-width: 400px;
            margin: 0 auto;
        }
        
        li {
            background-color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        li span {
            flex: 1;
        }
        
        li form {
            margin: 0;
        }
        
        li input[type="submit"] {
            background-color: #ccc;
            color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
        }
        
        li input[type="submit"]:hover {
            background-color: #999;
        }
    </style>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="text" name="task" placeholder="Enter a task">
        <input type="submit" value="Add Task">
    </form>
    <?php
    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<span>" . ($row['completed'] ? '<strike>' : '') . $row['task'] . ($row['completed'] ? '</strike>' : '') . "</span>";
            if (!$row['completed']) {
                echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' style='display:inline;'>";
                echo "<input type='hidden' name='complete' value='" . $row['id'] . "'>";
                echo "<input type='submit' value='Complete'>";
                echo "</form>";
            }
            echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' style='display:inline;'>";
            echo "<input type='hidden' name='delete' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "No tasks added yet.";
    }

    $conn->close();
    ?>



</body>

</html>