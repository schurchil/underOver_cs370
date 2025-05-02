<?php include_once("header.php"); ?>
<?php include_once("connectDB.php");
global $conn;
// imports employee
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Employee CSV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
</head>
<body>


<?php
$message = "";
$dataRows = [];
$csvHeader = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        if ($fileExtension === 'csv') {
            if (($handle = fopen($fileTmpPath, "r")) !== false) {
                $csvHeader = fgetcsv($handle, 1000, ",", '"', "\\");
                // saving column headers for formatting

                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
                    if (count(array_filter($row)) === 0) continue;
                    $row = array_map('trim', $row);  // clean the rows
                    $dataRows[] = $row;


                    $employee_id = intval($row[0]);
                    $nameParts = explode(" ", mysqli_real_escape_string($conn, $row[1]), 2);
                    $first_name = $nameParts[0];
                    $last_name = isset($nameParts[1]) ? $nameParts[1] : '';
                    $email = strtolower(mysqli_real_escape_string($conn, $row[2]));

                    $dept_id = intval($row[3]);
                    $dept_name = mysqli_real_escape_string($conn, $row[4]);
                    $manager_name = mysqli_real_escape_string($conn, $row[5]);

                    //Department updates and inserts ig
                    $dept_sql = "
                        INSERT INTO department (DepartmentID, Address, PhoneNumber, Status, DepartmentHeadID)
                        VALUES ('$dept_id', '123 fear st', '000-000-0000', 'active', '$employee_id')
                        ON DUPLICATE KEY UPDATE
                            Address = '123 Default St',
                            PhoneNumber = '000-000-0000',  
                            Status = 'active',
                            DepartmentHeadID = '$employee_id';
                        ";
                    mysqli_query($conn, $dept_sql);

                    // Employee updates
                    $emp_sql = "
                        INSERT INTO employee (EmployeeID, FirstName, LastName, Email, DepartmentID)
                        VALUES ('$employee_id', '$first_name', '$last_name', '$email', '$dept_id')
                        ON DUPLICATE KEY UPDATE
                            EmployeeID = '$employee_id',
                            FirstName = '$first_name',
                            LastName = '$last_name',
                            Email = '$email',
                            DepartmentID = '$dept_id';
                    ";
                    $result = mysqli_query($conn, $emp_sql);

                }

                fclose($handle);
                $message = "<div class='alert alert-success'>CSV uploaded and processed successfully!</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'> Please upload a valid CSV file.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'> Error uploading file.</div>";
    }
}
?>


<div class="container mt-5">
    <h2>Upload Employee CSV File</h2>
    <p class="mb-3">Choose a CSV file to upload and process.</p>

    <?php echo $message; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="csv_file" class="form-label">CSV File</label>
            <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <?php if (!empty($dataRows)): ?>
        <h3 class="mt-5">CSV Contents</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                <tr>
                    <?php foreach ($csvHeader as $header): ?>
                        <th><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataRows as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?= htmlspecialchars($cell) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

