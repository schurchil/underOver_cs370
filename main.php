<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Background.css">
    <title>Bank Employee Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
    <!--custom style to add visual flair to stuff -->
    <style>
        .flair {
            color: #325d88;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>

<?php include_once("header.php"); ?>

<main class="container my-5">
    <div class="text-center mb-1">
        <h1 class="display-4">Welcome to <span class="flair">Cisterns NearStop</span></h1>
        <p class="lead">Brightening Our Shareholder's Financial Future with Account Holders' Money</p>
    </div>

    <div class="card shadow-lg mx-auto" style="max-width: 700px;">
        <div class="card-body">
            <h4 class="card-title text-center">Our Mission</h4>
            <p class="card-text text-center">
                At <span class="flair">Cisterns NearStop Bank</span>, our mission is to provide seamless, secure, and
                intelligent banking solutions with cutting-edge technology, empowering our employees to make
                a profit for our parent company.
            </p>
        </div>
    </div>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="uploadAcct.php" class="btn btn-primary btn-lg">Upload Account CSVs</a>
        <a href="uploadLoan.php" class="btn btn-secondary btn-lg">Upload Loan CSVs</a>
        <a href="uploadTransact.php" class="btn btn-success btn-lg">Upload Employee CSVs</a>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


