<?php
include 'inc/admin.php';
$user = check_user();


if (isset($_GET['id'])) {
    $target = query("SELECT *,(SELECT v2 FROM `gr_options` WHERE type = 'profile' and v1 = 'name' and  v3 = gr_site_users.user_id) as nickname FROM `gr_site_users` WHERE user_id=".$_GET['id']);
} else {
    header('Location: index.php');
    exit;
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
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
                        <a class="nav-link" href="users.php" data='users'>
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
                        <a class="nav-link" href="withdrawal.php" data='withdrawal'>
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
                    <h1 class="mt-4">Details</h1>

                    <div class="row">
                       
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                   <?php echo $target[0]['nickname']; ?> All Logs
                                </div>

                                <?php if ($target[0]['agency']==1): ?>
                                
                                <div class="card-body">
                                    <h2 style="width:100%">Withdrawal logs</h2>
                                     <table class="table">
                                        <thead>
                                            <tr><th>Money Earned</th><th>Details</th><th>Paypal</th><th>Request date</th><th>Sending date</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                             <?php 
                                             $last_orders = query("SELECT * FROM `gr_withdrawals` WHERE uid = ".$_GET['id'].' ORDER BY `gr_withdrawals`.`requestDate` DESC ');
                                             foreach ($last_orders as $key => $value): ?>
                                                <tr>
                                                    <td><?php echo $value['earnedMoney']; ?></td>
                                                    <td><?php echo $value['details']; ?></td>
                                                    <td><?php echo $value['paypal']; ?></td>
                                                    <td><?php echo $value['requestDate']; ?></td>
                                                    <td><?php echo $value['sentDate']; ?></td>
                                                    <td><?php echo $value['status']; ?></td>
                                                </tr>
                                             <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div> 


                                <div class="card-body">
                                     <h2 style="width:100%">Agency Tokens Logs</h2>
                                     <table class="table">
                                        <thead>
                                            <tr><th>Token</th><th>Details</th><th>Date</th></tr>
                                        </thead>
                                        <tbody>
                                             <?php 
                                             $last_orders = query("SELECT * FROM `gr_agency_earnings` WHERE uid = ".$_GET['id'].' ORDER BY `gr_agency_earnings`.`earn_time` DESC ');
                                             foreach ($last_orders as $key => $value): ?>
                                                <tr>
                                                    <td><?php echo $value['coins']; ?></td>
                                                    <td><?php echo $value['details']; ?></td>
                                                    <td><?php echo $value['earn_time']; ?></td>
                                                </tr>
                                             <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div> 


                                <?php endif ?>

                                
                                <div class="card-body">
                                    <h2 style="width:100%">Order Logs</h2>
                                	 <table class="table">
                                		<thead>
                                			<tr><th>Method</th><th>Details</th><th>Price</th><th>Time</th></tr>
                                		</thead>
                                		<tbody>
                                			 <?php 
                                			 $last_orders = query("SELECT * FROM `gr_order_records` WHERE uid = ".$_GET['id'].' ORDER BY `gr_order_records`.`action_time` DESC ');
                                			 foreach ($last_orders as $key => $value): ?>
                                			 	<tr>
                                			 		<td><?php echo $value['method']; ?></td>
                                			 		<td><?php echo $value['details']; ?></td>
                                			 		<td><?php echo $value['price']; ?></td>
                                			 		<td><?php echo $value['action_time']; ?></td>
                                			 	</tr>
                                			 <?php endforeach ?>
                                		</tbody>
                                	</table>
                                </div>

                                
                                <div class="card-body">
                                    <h2 style="width:100%">Used Credits History</h2>
                                     <table class="table">
                                        <thead>
                                            <tr><th>Detail</th><th>Credits</th><th>Time</th></tr>
                                        </thead>
                                        <tbody>
                                             <?php 
                                             $logss = query("SELECT * FROM `gr_credit_used` WHERE uid = ".$_GET['id'].' ORDER BY `gr_credit_used`.`used_time` DESC ');
                                             foreach ($logss as $key => $value): ?>
                                                <tr>
                                                    <td><?php echo $value['details']; ?></td>
                                                    <td><?php echo $value['credit']; ?></td>
                                                    <td><?php echo $value['used_time']; ?></td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
</body>

</html>