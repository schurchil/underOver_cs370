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


$sql_accounts = "SELECT * FROM user ORDER BY CustomerID";
$result_accounts = $conn->query($sql_accounts);


if ($result_accounts->num_rows > 0): ?>
    <div class="container mt-4">
        <h3>User_Account_Loan</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-striped">
                <thead class="table-dark">
                <tr class="SUBCAT">
                    <th>CustomerID</th>
                    <th>FirstName</th>
                    <th>LastName</th>
                    <th>Email</th>
                    <th>PhoneNumber</th>
                    <th>Birthdate</th>
                    <th>SSN</th>


                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result_accounts->fetch_assoc()):
                    $customerID = $row["CustomerID"];
                    ?>
                    <!-- Account Info -->
                    <tr>
                        <td><?= htmlspecialchars(isset($row["CustomerID"]) ? $row["CustomerID"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["FirstName"]) ? $row["FirstName"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["LastName"]) ? $row["LastName"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["Email"]) ? $row["Email"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["PhoneNumber"]) ? $row["PhoneNumber"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["Birthdate"]) ? $row["Birthdate"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["SSN"]) ? $row["SSN"] : "") ?></td>
                    </tr>


                    <!-- ACCT Info -->
                    <tr class="SUBCAT">
                        <td  colspan = "2"></td>
                        <th>AccountID</th>
                        <th>AccountName</th>
                        <th>AccountType</th>
                        <th>Balance</th>
                        <th>IsSubAcc</th>
                    </tr>
                    <?php
                    $sql_cards = "SELECT * FROM Account WHERE CustomerID = $customerID";
                    $result_cards = $conn->query($sql_cards);
                    if ($result_cards && $result_cards->num_rows > 0):
                        while ($card = $result_cards->fetch_assoc()): ?>
                            <tr class="table-light">
                                <td colspan="2"></td>
                                <td><?= htmlspecialchars(isset($card["AccountID"]) ? $card["AccountID"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["AccountName"]) ? $card["AccountName"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["AccountType"]) ? $card["AccountType"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["Balance"]) ? $card["Balance"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["IsSubAcc"])? $card["IsSubAcc"]: "") ?></td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr class="table-light">
                            <td colspan="2"></td>
                            <td colspan="4"><em>No Cards on account</em></td>
                        </tr>
                    <?php endif; ?>


                    <!-- Transaction Info -->
                    <tr class="SUBCAT">
                        <td colspan = "2"></td>
                        <th>LoanID</th>
                        <th>LoanType</th>
                        <th>OrigAmnt</th>
                        <th>InterestRate</th>
                        <th>Status</th>
                        <th>StartDate</th>
                        <th>DueDate</th>
                    </tr>
                    <?php
                    $sql_tx = "SELECT * FROM loan WHERE CustomerID = $customerID";
                    $result_tx = $conn->query($sql_tx);
                    if ($result_tx && $result_tx->num_rows > 0):
                        while ($tx = $result_tx->fetch_assoc()): ?>
                            <tr class="table-light">
                                <td colspan ="2"></td>
                                <td><?= htmlspecialchars(isset($tx["LoanID"]) ? $tx["LoanID"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["LoanType"]) ? $tx["LoanType"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["OrigAmnt"]) ? $tx["OrigAmnt"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["InterestRate"]) ? $tx["InterestRate"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["Status"]) ? $tx["Status"] : "NULL") ?></td>
                                <td><?= htmlspecialchars(isset($tx["StartDate"]) ? $tx["StartDate"] : "NULL") ?></td>
                                <td><?= htmlspecialchars(isset($tx["DueDate"]) ? $tx["DueDate"] : "NULL") ?></td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr class="table-light">
                            <td></td><td></td>
                            <td colspan="6"><em>No Transactions on account</em></td>
                        </tr>
                    <?php endif; ?>


                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="container mt-5">
        <p>No results found.</p>
    </div>
<?php endif;
$conn->close();
?>
</body>
</html>
