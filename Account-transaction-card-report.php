<?php

include_once("header.php");
include_once("db.php");

$message = "";
$dataRows = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileNameCmps = explode(".", $_FILES['csv_file']['name']);
        $fileExtension = strtolower(end($fileNameCmps));

        if ($fileExtension === 'csv') {
            if (($handle = fopen($fileTmpPath, "r")) !== false) {
                $header = fgetcsv($handle, 1000, ",", '"', "\\"); // skip header row
                $pdo->beginTransaction(); // faster for many inserts

                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
                    $account_id = trim($row[0]);
                    $customer_id = trim($row[1]);
                    $balance = trim($row[2]);
                    $opened_date = trim($row[3]);

                    $sql = "INSERT INTO account (account_id, customer_id, balance, opened_date)
                            VALUES (:account_id, :customer_id, :balance, :opened_date)
                            ON DUPLICATE KEY UPDATE
                                customer_id = VALUES(customer_id),
                                balance = VALUES(balance),
                                opened_date = VALUES(opened_date)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':account_id' => $account_id,
                        ':customer_id' => $customer_id,
                        ':balance' => $balance,
                        ':opened_date' => $opened_date
                    ]);
                }
                $pdo->commit();
                fclose($handle);
                $message = "<div class='alert alert-success'>CSV uploaded and data inserted successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to open file.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>Please upload a valid CSV file.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>File upload error.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Accounts CSV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Upload Accounts CSV</h2>
    <?= $message ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="csv_file" class="form-label">CSV File</label>
            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload & Insert</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

