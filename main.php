
<?php include_once("header.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- The "missing locally stored library" is ok - it just advises against using CDNs, but should be
just fine for development cases: if we want to have offline support or avoid dependency on external
networks we would put the bootswatch/bootstrap stuff directly in our project-->
<html lang="en">

<header>
    <h1 style="font-size:120px;" class="text-right mb-3"  style=" font-family: 'Courier New'">Online Bank</h1>
    <p class="text-right text-muted"> Welcome Bank Administrator!</p>

</header>


<body>
        <div class="container py-5">
            <h4 class="row justify-content-right">
                <h4> Upload new CVS: </h4>
                <div class="col-md-6">
                    <div class="d-grid gap-3">
                        <a href="uploadAcct.php" class="btn btn-primary btn-lg">Account</a>
                        <a href="uploadLoan.php" class="btn btn-success btn-lg">Loan</a>
                        <a href="uploadTransactions.php" class="btn btn-info btn-lg">Transactions</a>

                    </div>
                </div>
            </div>
        </div>



</body>>


</html>


