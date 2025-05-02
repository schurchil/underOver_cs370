<?php include_once("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Acct</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
    <style>
        td, th {
            white-space: nowrap;
            vertical-align: middle;
        }


        h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }


        .SUBCAT th {
            font-weight: bold;
            background-color: #6c757d !important;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
<?php
include_once("connectDB.php");
global $conn;


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql_departments = "SELECT * FROM department ORDER BY DepartmentID";
$result_departments = $conn->query($sql_departments);


if ($result_departments->num_rows > 0): ?>
    <div class="container mt-4">
        <h3>Department_Manager_Employee</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-striped">
                <thead class="table-dark">
                <tr class="SUBCAT">
                    <th>DepartmentID</th>
                    <th>Address</th>
                    <th>PhoneNumber</th>
                    <th>Status</th>
                    <th>DepartmentHeadID</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result_departments->fetch_assoc()):
                    $deptID = $row["DepartmentID"];
                    $deptHeadID = $row["DepartmentHeadID"];
                    ?>
                    <!-- Department Info -->
                    <tr>
                        <td><?= htmlspecialchars($deptID) ?></td>
                        <td><?= htmlspecialchars($row["Address"]) ?></td>
                        <td><?= htmlspecialchars($row["PhoneNumber"]) ?></td>
                        <td><?= htmlspecialchars($row["Status"]) ?></td>
                        <td><?= htmlspecialchars($deptHeadID) ?></td>
                    </tr>


                    <!-- Employees -->
                    <tr class="SUBCAT">
                        <td colspan="1"></td>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>Email</th>
                        <th>PhoneNumber</th>
                    </tr>
                    <?php
                    $sql_employees = "SELECT * FROM employee WHERE DepartmentID = $deptID";
                    $result_employees = $conn->query($sql_employees);
                    if ($result_employees && $result_employees->num_rows > 0):
                        while ($emp = $result_employees->fetch_assoc()): ?>
                            <tr class="table-light">
                                <td colspan="1"></td>
                                <td><?= htmlspecialchars($emp["FirstName"]) ?></td>
                                <td><?= htmlspecialchars($emp["LastName"]) ?></td>
                                <td><?= htmlspecialchars($emp["Email"]) ?></td>
                                <td><?= htmlspecialchars($emp["PhoneNumber"]) ?></td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr class="table-light">
                            <td colspan="5"><em>No employees in this department</em></td>
                        </tr>
                    <?php endif; ?>


                    <!-- Manager Info -->
                    <tr class="SUBCAT">
                        <td colspan="2"></td>
                        <th>ManagerID</th>
                        <th>BranchID</th>
                        <th>StartDate</th>
                    </tr>
                    <?php
                    $sql_manager = "SELECT * FROM manager WHERE ManagerID = $deptHeadID";
                    $result_manager = $conn->query($sql_manager);
                    if ($result_manager && $result_manager->num_rows > 0):
                        while ($mgr = $result_manager->fetch_assoc()): ?>
                            <tr class="table-light">
                                <td colspan="2"></td>
                                <td><?= htmlspecialchars(isset($mgr["ManagerID"]) ? $mgr["ManagerID"] : "-") ?></td>
                                <td><?= htmlspecialchars(isset($mgr["BranchID"]) ? $mgr["BranchID"] : "-") ?></td>
                                <td><?= htmlspecialchars(isset($mgr["StartDate"]) ? $mgr["StartDate"] : "-") ?></td>


                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr class="table-light">
                            <td colspan="5"><em>No manager info found</em></td>
                        </tr>
                    <?php endif; ?>


                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="container mt-5">
        <p>No departments found.</p>
    </div>
<?php endif;
$conn->close();
?>
</body>
</html>
