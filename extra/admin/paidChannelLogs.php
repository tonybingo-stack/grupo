<?php
include 'inc/admin.php';
$user = check_user();


if (isset($_POST['action']) && isset($_POST['target']) && isset($_POST['credits']) && isset($_POST['channel'])&& isset($_POST['dated']) && isset($_POST['actionId'])) {

        $uid2 = $_POST['target'];
        $used = $_POST['credits'];
        $channel = $_POST['channel'];
        $dated = $_POST['dated'];
        $actionId = $_POST['actionId'];
        $msg1 = "$used credits have been added to your account by the system for channel #$channel dated $dated";
        query("INSERT INTO `gr_agency_earnings`(`uid`, `coins`, `details`, `paidID`) VALUES ($uid2,$used,'$msg1',$actionId)");
        query("UPDATE `gr_users` SET `agency_coins`=`agency_coins` + $used WHERE id=".$_POST['target']);

}


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
        thead, tbody, tfoot, tr, td, th {
            border-bottom: 1px solid #ccc!important;
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
                        <a class="nav-link active" href="paidChannelLogs.php" data='withdrawal'>
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
                    <h1 class="mt-4">Paid Channel Logs</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                    Channel Logs <a class="btn btn-warning" href="paidChannelLogs.php?status=1" style="float: right;">Show Closed Record</a>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Channel</th>
                                                <th>Spending</th>
                                                <th>Credits</th>
                                                <th>Assinged Agency</th>
                                                <th>Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            if (isset($_GET['status']))
                                                $status = $_GET['status'];
                                            else
                                                $status = 0;

                                            $channelLogs = query('SELECT gr_paidChannelLogs.*, (SELECT count(id) FROM `gr_agency_earnings` WHERE gr_agency_earnings.paidID =gr_paidChannelLogs.id) as totalRecord FROM `gr_paidChannelLogs` WHERE status = '.$status.' ORDER BY action_time DESC');

                                            foreach ($channelLogs as $key => $value): ?>
                                              <tr>
                                                <td><?php echo $value['id']; ?></td>
                                                <td><?php echo $value['channel']; ?></td>
                                                <td><?php echo $value['username']; ?></td>
                                                <td><?php echo $value['used_credits']; ?></td>
                                                <td><a href="showPaidRecords.php?id=<?php echo $value['id']; ?>" class="btn btn-primary btn-sm" >Show <?php echo $value['totalRecord']; ?> Record</a></td>
                                                <td><?php echo $value['action_time']; ?></td>
                                                <td style="text-align: center;">
                                                    <a href="#" class="btn btn-sm btn-success assingAgency" 
                                                    credit='<?php echo $value['used_credits']; ?>'
                                                    cid='<?php echo $value['id']; ?>'
                                                    dated='<?php echo $value['action_time']; ?>'
                                                    channel='<?php echo $value['channel']; ?>'
                                                    >Assignment To Agency</a></br>
                                                    <a href="paidChannelLogs.php?close=1&id=<?php echo $value['id']; ?>" class="btn btn-sm btn-danger" style="margin-top: 3px;">Close This record</a>
                                                </td>
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

    <div class="modal fade" id="assignment" tabindex="-1" aria-labelledby="assignment" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignment">Assingment paid channel record to agency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="paidChannelLogs.php" method="POST">
                        <input type="hidden" name="action" value='assign'>
                        <input type="hidden" name="actionId" value='assign'>
                        <input type="hidden" name="dated" value='assign'>
                        <input type="hidden" name="channel" value='assign'>




                        <div class="form-group">
                            <label for="AgencySelect">Agency</label>
                            <select class="form-control" id="AgencySelect" name='target' required="yes">
                                 <option>Select an Agency</option>
                                <?php 
                                $agencies = query("SELECT id,(SELECT v2 FROM `gr_options` WHERE type = 'profile' and v1 = 'name' and  v3 = gr_users.id) as nickname FROM `gr_users` WHERE agency = 1");

                                foreach ($agencies as $key => $value): ?>
                                <option value='<?php echo $value['id']; ?>'><?php echo $value['nickname']; ?></option>
                                <?php endforeach ?>
                              
                             
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Credit to be given</label>
                            <input type="number" class="form-control" name='credits' placeholder="30" required="true">
                        </div>
                        <button type="submit" class="btn btn-success" style="margin: 20px auto;">Save</button>
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
    var assignment = new bootstrap.Modal(document.getElementById('assignment'));
    
    </script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $(document).ready(function() {

        $('body').on( 'click', '.assingAgency', function () {
           $('#assignment input[name="credits"]').val($(this).attr('credit'));
           $('#assignment input[name="actionId"]').val($(this).attr('cid'));
           $('#assignment input[name="dated"]').val($(this).attr('dated'));
           $('#assignment input[name="channel"]').val($(this).attr('channel'));
           assignment.show();
        });

        

        
    });
    </script>
</body>

</html>