<?php
require_once('process/dbh.php');

$assignment_message = '';
$assignment_type = '';

// STEP 1: Fetch all unassigned projects (eid is NULL or empty)
$sql_projects = "SELECT * FROM `project` WHERE `eid` IS NULL OR `eid` = '' ORDER BY `duedate` ASC";
$result_projects = mysqli_query($conn, $sql_projects);

// STEP 2: Fetch employees who do not have any 'Due' project assigned
$sql_employees = "
    SELECT e.id
    FROM employee e
    WHERE NOT EXISTS (
        SELECT 1 FROM project p WHERE p.eid = e.id AND p.status = 'Due'
    )
";
$result_employees = mysqli_query($conn, $sql_employees);

// STEP 3: Prepare employee project counts (eligible employees only)
$employee_project_counts = [];
while ($emp = mysqli_fetch_assoc($result_employees)) {
    $emp_id = $emp['id'];
    $employee_project_counts[$emp_id] = 0;
}

// STEP 4: Assign each unassigned project to available employee
$assigned_any = false;
while ($project = mysqli_fetch_assoc($result_projects)) {
    if (empty($employee_project_counts)) {
        break;
    }

    asort($employee_project_counts);
    reset($employee_project_counts);
    $best_emp_id = key($employee_project_counts);

    $pid = $project['pid'];
    $assign_query = "
        UPDATE `project` 
        SET `eid` = '$best_emp_id', `status` = 'Due' 
        WHERE `pid` = '$pid'
    ";
    if (mysqli_query($conn, $assign_query)) {
        $employee_project_counts[$best_emp_id]++;
        $assigned_any = true;
    }
}

if ($assigned_any) {
    $assignment_message = 'Unassigned projects have been successfully assigned to available employees.';
    $assignment_type = 'success';
} else {
    $assignment_message = 'No available employees for assignment or no unassigned projects.';
    $assignment_type = 'error';
}

// STEP 5: Fetch all project assignments to display
$sql_all_projects = "
    SELECT p.*, e.dept, CONCAT(e.firstName, ' ', e.lastName) AS emp_name 
    FROM `project` p 
    LEFT JOIN `employee` e ON p.eid = e.id 
    ORDER BY p.duedate ASC
";
$result_all = mysqli_query($conn, $sql_all_projects);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Project Status | Admin Panel | Task Management System</title>
    <link rel="stylesheet" type="text/css" href="styleview.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        header nav {
            background-color: #2f2f2f;
            padding: 10px 0;
        }

        nav h1 {
            color: white;
            margin: 0;
            padding-left: 20px;
            display: inline-block;
        }

        #navli {
            list-style: none;
            float: right;
            margin-right: 20px;
        }

        #navli li {
            display: inline;
            margin: 0 10px;
        }

        .homeblack, .homered {
            color: white;
            text-decoration: none;
        }

        .homered {
            font-weight: bold;
            color: #ff5959;
        }

        .divider {
            height: 4px;
            background-color: #4CAF50;
        }

        .container {
            padding: 20px;
        }

        .message-box {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .message-box.success {
            background-color: #daeedfff;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 12px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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
            <li><a class="homeblack" href="assign.php">Assign Task</a></li>
            <li><a class="homered" href="assignproject.php">Task Status</a></li>
            <li><a class="homeblack" href="empleave.php">Employee Leave</a></li>
            <li><a class="homeblack" href="alogin.html">Log Out</a></li>
        </ul>
    </nav>
</header>

<div class="divider"></div>

<div class="container">
    <h2 style="text-align:center;">Task Assignment Status</h2>

    <?php if (!empty($assignment_message)): ?>
        <div class="message-box <?= $assignment_type ?>">
            <?= $assignment_message ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>Task ID</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Task Name</th>
            <th>Details</th>
            <th>Due Date</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Mark</th>
            <th>Option</th>
        </tr>

        <?php while ($project = mysqli_fetch_assoc($result_all)) : ?>
            <tr>
                <td><?= $project['pid'] ?></td>
                <td><?= $project['emp_name'] ?: 'Unassigned' ?></td>
                <td><?= $project['dept'] ?: 'Unassigned' ?></td>
                <td><?= htmlspecialchars($project['pname']) ?></td>
                <td><?= htmlspecialchars($project['detail']) ?></td>
                <td><?= $project['duedate'] ?></td>
                <td><?= $project['subdate'] ?: 'N/A' ?></td>
                <td><?= $project['status'] ?: 'Due' ?></td>
                <td><?= $project['mark'] !== null ? $project['mark'] : 'N/A' ?></td>
                <td>
                    <?php if (!empty($project['eid'])): ?>
                        <a href="mark.php?id=<?= $project['eid'] ?>&pid=<?= $project['pid'] ?>">Mark</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
