<?php
require_once('process/dbh.php');

// STEP 1: Handle form submission
$success_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $duedate = mysqli_real_escape_string($conn, $_POST['duedate']);
    $subdate = NULL; // Initially NULL
    $status = 'Due'; // Default status

    // Insert project into the database
    $sql = "INSERT INTO project (pname, detail, duedate, subdate, status, eid) 
            VALUES ('$pname', '$detail', '$duedate', NULL, '$status', NULL)";

    if (mysqli_query($conn, $sql)) {
        $pid = mysqli_insert_id($conn);

        $sql_employees = "SELECT id FROM employee WHERE id NOT IN (SELECT eid FROM project WHERE status = 'Due')";
        $result_employees = mysqli_query($conn, $sql_employees);

        if ($result_employees && mysqli_num_rows($result_employees) > 0) {
            $employee = mysqli_fetch_assoc($result_employees);
            $emp_id = $employee['id'];

            $assign_query = "UPDATE project SET eid = '$emp_id' WHERE pid = '$pid'";
            if (mysqli_query($conn, $assign_query)) {
                $success_message = 'Project assigned successfully!';
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'assignproject.php';
                    }, 2000);
                </script>";
            }
        } else {
            $success_message = 'Project created and assigned to available employee.';
        }
    }
}

// Fetch distinct departments from the employee table
$dept_sql = "SELECT DISTINCT dept FROM employee";
$dept_result = mysqli_query($conn, $dept_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Project | Admin Panel | Task Management System</title>
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">
    <link href="css/main.css" rel="stylesheet" media="all">
    <style>
        select[name="dept"], textarea[name="detail"], .input-group label {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            font-size: 16px;
        }
        .input-group label {
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 15px;
        }
        textarea[name="detail"] {
            height: 100px;
            resize: vertical;
        }
        .success-message {
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px auto;
            width: 90%;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <h1>T.M.S</h1>
            <ul id="navli">
                <li><a class="homeblack" href="aloginwel.php">HOME</a></li>
                <li><a class="homeblack" href="addemp.php">Add Employee</a></li>
                <li><a class="homeblack" href="viewemp.php">View Employee</a></li>
                <li><a class="homered" href="assign.php">Assign Task</a></li>
                <li><a class="homeblack" href="assignproject.php">Task Status</a></li>

                <li><a class="homeblack" href="empleave.php">Employee Leave</a></li>
                <li><a class="homeblack" href="alogin.html">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="divider"></div>

    <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
        <div class="wrapper wrapper--w680">
            <div class="card card-1">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Assign Task</h2>

                    <?php if (!empty($success_message)) : ?>
                        <div class="success-message"><?= $success_message ?></div>
                    <?php endif; ?>

                    <form action="assign.php" method="POST" enctype="multipart/form-data">
                        <div class="input-group">
                            <label for="dept">Select Department</label>
                            <select name="dept" required>
                                <option disabled selected value="">-- Select Department --</option>
                                <?php while ($row = mysqli_fetch_assoc($dept_result)) {
                                    echo "<option value=\"{$row['dept']}\">{$row['dept']}</option>";
                                } ?>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="pname">Task Name</label>
                            <input class="input--style-1" type="text" placeholder="Task Name" name="tname" required>
                        </div>

                        <div class="input-group">
                            <label for="detail">Task Details</label>
                            <textarea name="detail" placeholder="Enter detailed description of the task..." required></textarea>
                        </div>

                        <div class="input-group">
                            <label for="duedate">Due Date</label>
                            <input class="input--style-1" type="date" name="duedate" required>
                        </div>

                        <div class="p-t-20">
                            <button class="btn btn--radius btn--green" type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>
    <script src="js/global.js"></script>
</body>
</html>
