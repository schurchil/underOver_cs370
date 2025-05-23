<?php include_once("header.php"); ?>
<?php include_once("connectDB.php");
global $conn;
// ACCOUNT AND USER AND CARD
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Account CSV</title>
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
                    $row = array_map('trim', $row);
                    $dataRows[] = $row;

                    // ACCOUNT TABLE COLS

                    $account_name = mysqli_real_escape_string($conn, $row[0]);
                    $account_type_raw = $row[1];
                    $account_type = ucfirst(strtolower($account_type_raw));
                    if ($account_type === 'Subacc') $account_type = 'SubAcc';
                    $allowedAccountTypes = ['Checking', 'Saving', 'SubAcc'];
                    if (!in_array($account_type, $allowedAccountTypes)) {
                        //echo "<div class='alert alert-danger'>Invalid AccountType: '$account_type_raw' → '$account_type'</div>";
                        continue;
                    }

                    $balance = floatval($row[2]);
                    $is_sub_acc = intval($row[3]);

                    // USER TABLE COLS

                    $customer_name = mysqli_real_escape_string($conn, $row[4]);
                    $customer_email = strtolower(mysqli_real_escape_string($conn, $row[5])); // make case-insensitive

                    $nameParts = explode(' ', $customer_name, 2);
                    $first_name = mysqli_real_escape_string($conn, $nameParts[0]);
                    $last_name = isset($nameParts[1]) ? mysqli_real_escape_string($conn, $nameParts[1]) : '';

                    $card_type_raw = mysqli_real_escape_string($conn, $row[6]);
                    echo $card_type_raw;
                    $card_type = ucfirst(strtolower($card_type_raw));
                    $allowedCardTypes = ['Credit', 'Debit'];
                    if (!in_array($card_type, $allowedCardTypes)) {
                        //echo "<div class='alert alert-danger'>Invalid cardType: '$card_type_raw' → '$card_type'</div>";
                        continue;
                    }
                    $expirationDate = mysqli_real_escape_string($conn, $row[7]);
                    $status = mysqli_real_escape_string($conn, $row[8]);

                    // Update and insert into USER table
                    // use dummy data to fill in the gaps, is that right???
                    $user_sql = "
                                INSERT INTO user ( FirstName, LastName, Email, PhoneNumber, Birthdate, SSN)
                                VALUES (, '$first_name', '$last_name', '$customer_email', '000-000-0000', '2000-01-01', '000-00-0000')
                                ON DUPLICATE KEY UPDATE
                                    FirstName = '$first_name',
                                    LastName = '$last_name',
                                    Email = '$customer_email';
                            ";
                    mysqli_query($conn, $user_sql);

                    // Update and insert into ACCOUNT table
                    $acct_sql = "
                            INSERT INTO account ( AccountName, AccountType, Balance, IsSubAcc)
                            VALUES (, '$account_name', '$account_type', '$balance', '$is_sub_acc')
                            ON DUPLICATE KEY UPDATE 
                                AccountName = '$account_name',
                                AccountType = '$account_type',
                                Balance = '$balance',
                                IsSubAcc = '$is_sub_acc',
                        ";
                    mysqli_query($conn, $acct_sql);

                    // update and insert into CARD table
                    $card_sql = "
                        INSERT INTO card( CardType, ExpirationDate, Status)
                        VALUES ('$card_type', '$expirationDate','$status')
                        ON DUPLICATE KEY UPDATE
                                         CardType = '$card_type',
                                         ExpirationDate = '$expirationDate',
                                         Status = '$status';
                    ";
                    mysqli_query($conn, $card_sql);
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
<h2>Upload Account CSV File</h2>
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
