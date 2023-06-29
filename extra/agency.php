<?php

include 'inc/db.php';
include 'extraconf.php';

$user = check_user();

$min = $minwithdrawal;

if ($user['agency'] == 0) {
    echo "You are not have access";
    exit();
} 


if (isset($_POST['paypal']) && ($user['agency_coins']*$user['agency_rate'])>$min) {
    $paypal = $_POST['paypal'];
    $credits = $user['agency_coins'];
    $earnedMoney = $user['agency_coins']*$user['agency_rate'];
    $uid2 = $user['id'];
    $sql = "INSERT INTO `gr_withdrawals`(`uid`, `credits`, `earnedMoney`, `paypal`) VALUES ($uid2,$credits,$earnedMoney,'$paypal')";
    query($sql);
    query('UPDATE `gr_users` SET  `agency_coins` = 0 WHERE id='.$uid2);
    header('Location: agency.php');
    exit;
}


$history =  query("SELECT * FROM `gr_agency_earnings` WHERE uid=".$user['id']." ORDER BY `gr_agency_earnings`.`earn_time` DESC LIMIT 100");
$requests =  query("SELECT * FROM `gr_withdrawals` WHERE uid=".$user['id']." ORDER BY `gr_withdrawals`.`requestDate` DESC LIMIT 100");






?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>SabayaChat</title>
</head>

<body>
    <div class="container py-3">
        <header>
            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h3 class="display-4 fw-normal">Earned Money</h3>
                <p class="fs-5 text-muted">
                    <?php echo $user['agency_coins']*$user['agency_rate']; ?> $</p>
                <?php if ($user['agency_coins']*$user['agency_rate']>$min): ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEarned">Submit a withdrawal request.</button>
                <?php else: ?>
                <button class="btn btn-warning">You need to earned min
                    <?php echo $min; ?>$ for withdrawal.</button>
                <?php endif ?>
            </div>
        </header>
        <main>
            <div class="row row-cols-1 row-cols-md-3 mb-3">
                <div class="col-12">
                    <div class="card mb-4 rounded-3 shadow-sm">
                        <div class="card-header py-3">
                            <h4 class="my-0 fw-normal">Active requests</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Earned</th>
                                            <th>Request Date</th>
                                            <th>Paypal Account</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $key => $value) { 
                                            if ($value['status']!=0)
                                                continue;
                                        ?>
                                        <tr>
                                            <td>
                                                $
                                                <?php echo $value['earnedMoney'];  ?>
                                            </td>
                                            <td>
                                                <?php echo $value['requestDate'];  ?>
                                            </td>
                                            <td>
                                                <?php echo $value['paypal'];  ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card mb-4 rounded-3 shadow-sm">
                        <div class="card-header py-3">
                            <h4 class="my-0 fw-normal">Old requests</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Earned</th>
                                            <th>Request Date</th>
                                            <th>Action Date</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $key => $value) { 
                                            if ($value['status']==0)
                                                continue;
                                        ?>
                                        <tr>
                                        <td>
                                            $
                                            <?php echo $value['earnedMoney'];  ?>
                                        </td>
                                        <td>
                                            <?php echo $value['requestDate'];  ?>
                                        </td>
                                        <td>
                                            <?php echo $value['sentDate'];  ?>
                                        </td>
                                        <td>
                                            <?php echo $value['details'];  ?>
                                        </td>
                                    </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="display-6 text-center mb-4">Earned History</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Earned</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <?php foreach ($history as $key => $value) { ?>
                    <tr>
                        <td>
                            <?php echo $value['details'];  ?>
                        </td>
                        <td>
                            $
                            <?php echo $value['coins']*$user['agency_rate'];  ?>
                        </td>
                        <td>
                            <?php echo $value['earn_time'];  ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <div class="modal fade" id="addEarned" tabindex="-1" aria-labelledby="addEarned2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEarned2">Create a withdrawal request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="agency.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Paypal Email address</label>
                            <input type="email" class="form-control" id="email" aria-describedby="email2" name='paypal'>
                            <div id="email2" class="form-text">You need a Paypal account for withdrawal.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>