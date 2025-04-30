<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Employee Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- this is for loading Bootswatch Sandstone theme for custom Bootstrap styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/sandstone/bootstrap.min.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Link brand button thing to main.php -->
        <a class="navbar-brand" href="main.php">The Big Data Bank</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Dropdown for actions -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Make a report
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="uploadAcct.php"> Account Report </a></li>
                        <li><a class="dropdown-item" href="uploadLoan.php">Loan Reports </a></li>
                        <li><a class="dropdown-item" href="uploadTransact.php">Employee Reports</a></li>
                    </ul>
                </li>
            </ul>

            <span class="navbar-text">
                Welcome, Employee
            </span>
        </div>
    </div>
</nav>
