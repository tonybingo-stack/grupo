<?php
include 'inc/admin.php';
$user = check_user();

if (isset($_GET['cancel']) && isset($_GET['id'])) {
    $check_request =  query("SELECT * FROM `gr_withdrawals` WHERE  status=0 and id=".$_GET['id']);

    if (count($check_request)==1) {
        $old = $check_request[0]['credits'];
        $tuid = $check_request[0]['uid'];
        query("UPDATE `gr_users` SET agency_coins=agency_coins + $old WHERE id=$tuid");
        query("UPDATE `gr_withdrawals` SET status=-1, `sentDate` = NOW(), details='Canceled.' WHERE id=".$_GET['id']);
    }
    header('Location: withdrawal.php');
    exit();
}


if (isset($_POST['wid']) && isset($_POST['details'])) {
    $check_request =  query("SELECT * FROM `gr_withdrawals` WHERE  status=0 and id=".$_POST['wid']);

    if (count($check_request)==1) {
        $details = $_POST['details'];
        $details = preg_replace("/'/", '', $details);
        query("UPDATE `gr_withdrawals` SET status=1, `sentDate` = NOW(), details='$details.' WHERE id=".$_POST['wid']);
    }
    header('Location: withdrawal.php');
    exit();
}


$requests =  query("SELECT * FROM `gr_withdrawals` WHERE  status=0 ORDER BY `gr_withdrawals`.`requestDate` DESC LIMIT 100");

?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style type="text/css">
    thead,
    tbody,
    tfoot,
    tr,
    td,
    th {
        border-bottom: 1px solid #ccc !important;
    }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand ps-3" href="index.php" data='dashboard'>Grupo Admin Extra</a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php" data='dashboard'>
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link " href="users.php" data='users'>
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="sub_list.php" data='subs_list'>
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Subs List
                        </a>
                        <a class="nav-link" href="orders.php" data='orders'>
                            <div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div>
                            Orders
                        </a>
                        <a class="nav-link" href="credits.php" data='credits'>
                            <div class="sb-nav-link-icon"><i class="fas fa-coins"></i></div>
                            Credit packages
                        </a>
                        <a class="nav-link" href="subs.php" data='subs'>
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Subs packages
                        </a>
                        <a class="nav-link" href="gifts.php" data='gifts'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gift"></i></div>
                            Gifts
                        </a>
                        <div class="sb-sidenav-menu-heading">Paid Channels</div>
                        <a class="nav-link" href="paidChannelLogs.php" data='withdrawal'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                            Paid Channel Process
                        </a>
                        <div class="sb-sidenav-menu-heading">Agencies</div>
                        <a class="nav-link" href="agencies.php" data='agencies'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                            Agencies List
                        </a>
                        <a class="nav-link active" href="withdrawal.php" data='withdrawal'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                            Withdrawal requests
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $user['display_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Request list</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                    Active requests.
                                    <a href="oldWithdrawal.php" class="btn btn-warning btn-sm" style="float:right;">Old processed request</a>
                                </div>
                                <div class="card-body">
                                    <table id="table2" class="table">
                                        <thead>
                                            <tr>
                                                <th>UserId</th>
                                                <th>Requested Amount</th>
                                                <th>Credit</th>
                                                <th>Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($requests as $key => $value): ?>
                                            <tr>
                                                <td><?php echo $value['uid']; ?></td>
                                                <td>$<?php echo $value['earnedMoney']; ?></td>
                                                <td><?php echo $value['credits']; ?></td>
                                                <td><?php echo $value['requestDate']; ?></td>
                                                <td><a class="btn btn-danger btn-sm"  href="withdrawal.php?cancel=1&id=<?php echo $value['id']; ?>">Cancel this action and restore credit.</a><button class="btn btn-success btn-sm setComplate" wId='<?php echo $value['id']; ?>'>Set as complete and enter details.</button></td>
                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2022</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <div class="modal fade" id="add_details" tabindex="-1" aria-labelledby="add_de" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_de">Add request details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="withdrawal.php" method="POST">
                        <input type="hidden" name="wid" id="wId">
                        <div class="form-floating">
                          <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="details" required="true"></textarea>
                          <label for="floatingTextarea2">Comments</label>
                        </div>
                        <button type="submit" class="btn btn-success" style="margin: 20px auto;">Add & Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    var add_details = new bootstrap.Modal(document.getElementById('add_details'));
    </script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $('.setComplate').click(function (e) {
            $('#wId').val($(this).attr('wId'));
            add_details.show();
        })
    </script>
</body>

</html>