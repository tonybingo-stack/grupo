<?php
include 'inc/admin.php';
$user = check_user();
$channelId = $_GET['id'];
$sql1 = "SELECT v2 as userId,(SELECT v2 as username FROM `gr_options` WHERE type = 'profile' and gr_options.v1 = 'name' and  gr_options.v3 =m.v2) as username FROM `gr_options` m WHERE `type` = 'gruser' and v1 = '$channelId'";
$channelUsers = query($sql1);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['id'] && is_array($_POST['user']))  {
        $chan = $_GET['id'];
        foreach ($_POST['user'] as $key => $value) {
           $sql = "UPDATE `gr_options` SET v5 = 1 WHERE `type` = 'gruser' and v2 = '$key' and v1 = '$chan'";
           query($sql);
        }
    }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
                    <?php echo $user['name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Channel Users</h1>
                    <h4 class="mt-4">
                        <?php echo urldecode($_GET['channel']) ?>
                    </h4>
                    <form method="post" action="#">
                        <div class="row">
                            <div class="col-12" style="margin-bottom: 10px;">
                                <button class="btn btn-primary btn-sm" onclick="selectAll();" type="button">Select All</button>
                                <button class="btn btn-warning btn-sm" type="submit" style="float: right;">Kick Selected Users</button>
                            </div>
                        </div>
                       
                        <div class="row">
                            <table class="table">
                                
                            <thead>
                                <tr>
                                    <th>-</th>
                                    <th>User Id</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($channelUsers as $key => $value): ?>
                                 <tr>
                            <td class="col-2"><input type="checkbox" name="user[<?php echo $value['userId']; ?>]" value="true"></td>
                            <td class="col-4">
                                <?php echo $value['userId']; ?>
                            </td>
                            <td><?php echo $value['username']; ?></td>
                            </tr>
                            <?php endforeach ?>
                            <?php if (count($channelUsers) == 0): ?>
                            <tr><td>No have user.</td></tr>
                            <?php endif ?>
                            </tbody>
                            </table>
                        </div>
                    </form>
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
    <script type="text/javascript">
    var all = false;

    function selectAll() {
        if (all == false) {
            $('input[type="checkbox"]').prop('checked', true);
            all = true;
        } else {
            $('input[type="checkbox"]').prop('checked', true);
            all = true;
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
</body>

</html>