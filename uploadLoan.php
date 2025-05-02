<?php include_once("header.php"); ?>
<?php include_once("connectDB.php");
global $conn;

// LOAN AND USER
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Loan CSV</title>
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

                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
                    $dataRows[] = $row;

                    // LOAN TABLE COLS
                    $loan_id = intval($row[0]);     // int
                    $customer_id = intval( $row[1]);  // int SHARED WITH USER
                    $loan_type = ucfirst(strtolower(trim($row[2]))); // make case-insensitive
                    $allowedLoanTypes = ['Personal', 'Auto'];
                    if (!in_array($loan_type, $allowedLoanTypes)) {   // check against options
                        continue;
                    }
                    $orig_amnt= floatval($row[3]);  // decimal
                    $interest_rate = floatval($row[4]);  // decimal
                    $status = strtoupper(trim($row[5])); // make case-insensitive
                    $allowedStatusTypes = ['UNPAID', 'PAID'];
                    if (!in_array($status, $allowedStatusTypes)) {   // check against options
                        continue;
                    }

                    $start_date = mysqli_real_escape_string($conn, $row[6]);  // date but treat like string
                    $startConvert = strtotime($start_date);   // needs to be converted to SQL date format to work with DB
                    $start_date = $startConvert ? date('Y-m-d H:i:s', $startConvert) : null;

                    $due_date = mysqli_real_escape_string($conn, $row[7]);  // date but treat like string
                    $dueConvert = strtotime($start_date);
                    $due_date = $dueConvert ? date('Y-m-d H:i:s', $dueConvert) : null;

                    // USER TABLE COLS
                    $customer_name = mysqli_real_escape_string($conn, $row[8]);
                    $customer_email = mysqli_real_escape_string($conn, $row[9]);

                    $nameParts = explode(' ', $customer_name, 2);
                    $first_name = mysqli_real_escape_string($conn, $nameParts[0]);
                    $last_name = isset($nameParts[1]) ? mysqli_real_escape_string($conn, $nameParts[1]) : '';



                    $loan_sql = "
                        INSERT INTO loan (LoanID, CustomerID, LoanType, OrigAmnt, InterestRate, Status, StartDate, DueDate )
                        VALUES ('$loan_id', '$customer_id', '$loan_type', '$orig_amnt', '$interest_rate', '$status', '$start_date', '$due_date')
                        ON DUPLICATE KEY UPDATE 
                            LoanID = '$loan_id',
                            CustomerID = '$customer_id',
                            LoanType = '$loan_type',
                            OrigAmnt = '$orig_amnt',
                            InterestRate = '$interest_rate', 
                            Status = '$status', 
                            StartDate = '$start_date',
                            DueDate = '$due_date';
                    ";
                    mysqli_query($conn, $loan_sql);

                    $user_sql ="
                    INSERT INTO user (CustomerID, FirstName, LastName, email, phoneNumber, birthdate, ssn)
                    VALUES ('$customer_id', '$first_name', '$last_name', '$customer_email', '000-000-0000', '2000-01-01','0000000000')
                    ON DUPLICATE KEY UPDATE 
                                    FirstName = '$first_name',
                                     LastName = '$last_name',
                                     email = '$customer_email';
                    ";

                    mysqli_query($conn, $user_sql);
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
    <h2>Upload Loan CSV File</h2>
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
