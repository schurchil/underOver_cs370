<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CNS Bank Employee Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- this is for loading Bootswatch Sandstone theme for custom Bootstrap styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
</head>

<body>
<!-- header.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="main.php">
            <img src="BankLogo.png" alt="Logo" width="30" height="30" >
            Cisterns NearStop Bank
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="uploadAcct.php">Upload Account CSV</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="uploadLoan.php">Upload Loan CSV</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="uploadTransact.php">Upload Transactions CSV</a>
                </li>
            </ul>
            <span class="navbar-text">Welcome, Employee</span>
        </div>
    </div>
</nav>

<header class="bg-primary text-white py-2">
    <div class="container text-center">
        <h2 class="mb-1 fs-5">Employee Portal - Peoria Branch</h2>
    </div>
</header>

