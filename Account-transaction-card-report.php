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


$sql_accounts = "SELECT * FROM account ORDER BY AccountID";
$result_accounts = $conn->query($sql_accounts);


if ($result_accounts->num_rows > 0): ?>
    <div class="container mt-4">
        <h3>Account_Card_Transaction Report</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-striped">
                <thead class="table-dark">
                <tr class="SUBCAT">
                    <th>AccountID</th>
                    <th>AccountName</th>
                    <th>AccountType</th>
                    <th>Balance</th>
                    <th>IsSubAcc</th>
                    <th>CustomerID</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result_accounts->fetch_assoc()):
                    $acctID = $row["AccountID"];
                    ?>
                    <!-- Account Info -->
                    <tr>
                        <td><?= htmlspecialchars($acctID) ?></td>
                        <td><?= htmlspecialchars(isset($row["AccountName"]) ? $row["AccountName"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["AccountType"]) ? $row["AccountType"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["Balance"]) ? $row["Balance"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["IsSubAcc"]) ? $row["IsSubAcc"] : "") ?></td>
                        <td><?= htmlspecialchars(isset($row["CustomerID"]) ? $row["CustomerID"] : "") ?></td>
                    </tr>


                    <!-- Card Info -->
                    <tr class="SUBCAT">
                        <td  colspan = "2"></td>
                        <th>CardNumber</th>
                        <th>CardType</th>
                        <th>ExpirationDate</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $sql_cards = "SELECT * FROM card WHERE AccountID = $acctID";
                    $result_cards = $conn->query($sql_cards);
                    if ($result_cards && $result_cards->num_rows > 0):
                        while ($card = $result_cards->fetch_assoc()): ?>
                            <tr class="table-light">
                                <td colspan="2"></td>
                                <td><?= htmlspecialchars(isset($card["CardNumber"]) ? $card["CardNumber"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["CardType"]) ? $card["CardType"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["ExpirationDate"]) ? $card["ExpirationDate"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($card["Status"]) ? $card["Status"] : "") ?></td>
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
                        <th>TransactionID</th>
                        <th>TimeStamp</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>toAcct</th>
                    </tr>
                    <?php
                    $sql_tx = "SELECT * FROM transaction WHERE fromAcct = $acctID";
                    $result_tx = $conn->query($sql_tx);
                    if ($result_tx && $result_tx->num_rows > 0):
                        while ($tx = $result_tx->fetch_assoc()): ?>
                            <tr class="table-light">
                                <td colspan ="2"></td>
                                <td><?= htmlspecialchars(isset($tx["TransactionID"]) ? $tx["TransactionID"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["TimeStamp"]) ? $tx["TimeStamp"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["Amount"]) ? $tx["Amount"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["Type"]) ? $tx["Type"] : "") ?></td>
                                <td><?= htmlspecialchars(isset($tx["Description"]) ? $tx["Description"] : "NULL") ?></td>
                                <td><?= htmlspecialchars(isset($tx["toAcct"]) ? $tx["toAcct"] : "NULL") ?></td>
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

