<!--HTML for header and basic page stuff-->
<?php include_once("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Transaction CSV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
</head>
<body>



<?php
//PHP LAND
// Handle file upload
$message = "";
$dataRows = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK)
    {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        $fileSize = $_FILES['csv_file']['size'];
        $fileType = $_FILES['csv_file']['type'];

        // get the extension off the file, so that we can check if it's a csv
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        //if the user actually uploaded a CSV
        if ($fileExtension === 'csv')
        {
            //try to open and read the file (basic check)
            if (($handle = fopen($fileTmpPath, "r")) !== false)
            {
                while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
                    $dataRows[] = $row;
                }
                fclose($handle);
                $message = "<div class='alert alert-success'>CSV uploaded and read successfully!</div>";
            }
            else
            {
                $message = "<div class='alert alert-danger'>Failed to read the uploaded file.</div>";
            }
        }
        else
        {
            $message = "<div class='alert alert-warning'>Please upload a valid CSV file.</div>";
        }
    }
    else
    {
        $message = "<div class='alert alert-danger'>Error uploading file.</div>";
    }
}
?>

<!-- HTML - for the buttons -->
<!-- container is a Bootstrap class that adds padding/margins. mt-5 = margin-top: 5 units. -->
<div class="container mt-5">

    <h2>Upload CSV File</h2>
    <p class="mb-3">Choose a CSV file to upload and process.</p>

    <!-- take the message we made in php land and display it -->
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
                    <?php foreach ($dataRows[0] as $header): ?>
                        <th><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach (array_slice($dataRows, 1) as $row): ?>
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
