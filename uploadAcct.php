<?php include_once("header.php"); ?>
<?php include_once("connectDB.php");
global $conn;
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

                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
                    $dataRows[] = $row;

                    $account_id = intval($row[0]);
                    $account_name = mysqli_real_escape_string($conn, $row[1]);
                    $account_type = mysqli_real_escape_string($conn, $row[2]);
                    $balance = floatval($row[3]);
                    $is_sub_acc = intval($row[4]);
                    $customer_id = intval($row[5]);

                    $sql = "
                        INSERT INTO account (AccountID, AccountName, AccountType, Balance, IsSubAcc, CustomerID)
                        VALUES ('$account_id', '$account_name', '$account_type', '$balance', '$is_sub_acc', '$customer_id')
                        ON DUPLICATE KEY UPDATE 
                            AccountName = '$account_name',
                            AccountType = '$account_type',
                            Balance = '$balance',
                            IsSubAcc = '$is_sub_acc',
                            CustomerID = '$customer_id';
                    ";

                    mysqli_query($conn, $sql);
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
    <h2>Upload CSV File</h2>
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
