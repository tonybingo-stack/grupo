<?php
include 'inc/admin.php';
$user = check_user();


if (isset($_POST['action'])) {

    if ($_POST['action'] == 'credits' && isset($_POST['user_id']) && isset($_POST['credits'])) {
        add_credit($_POST['user_id'],intval($_POST['credits']));
    }

    if ($_POST['action'] == 'subs' && isset($_POST['user_id']) && isset($_POST['plans']) && isset($_POST['days'])) {
        add_sub($_POST['user_id'],intval($_POST['plans']),intval($_POST['days']));
    }
}
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'make_agency' && isset($_GET['id'])) {
        query('UPDATE `gr_site_users` SET agency = 1 WHERE user_id='.$_GET['id']);
    }
    if ($_GET['action'] == 'remove_agency' && isset($_GET['id'])) {
        query('UPDATE `gr_site_users` SET agency = 0 WHERE user_id='.$_GET['id']);
    }
    header('Location: users.php');
    exit;
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
                        <a class="nav-link active" href="users.php" data='users'>
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
                    <h1 class="mt-4">Users List</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                    Users
                                </div>
                                <div class="card-body">
                                    <table id="table2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Nickname</th>
                                                <th>Role</th>
                                                <th>Credit</th>
                                                <th>Subs</th>
                                                <th>Subs End</th>
                                                <th>Agency</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
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
    <div class="modal fade" id="add_subs" tabindex="-1" aria-labelledby="add_subs" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_subs">Subs Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="users.php" method="POST">
                        <input type="hidden" name="action" value='subs'>
                        <input type="hidden" name="user_id">
                        <div class="form-group">
                            <label>Nick</label>
                            <input type="text" class="form-control" name='nick' disabled="true">
                        </div>
                        <div class="form-group">
                            <label for="plans">Select Subs packages</label>
                            <select class="form-control" id="plans" required="true" name="plans">
                                <?php 
                                $plans = plans();
                                foreach ($plans as $key => $value): ?>
                                <option value="<?php echo $value['id']; ?>">
                                    <?php echo $value['name']; ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Expired Day</label>
                            <input type="number" class="form-control" name='days' placeholder="30" required="true">
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
    <div class="modal fade" id="add_credit" tabindex="-1" aria-labelledby="add_subs" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_subs">Subs Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="users.php" method="POST">
                        <input type="hidden" name="action" value='credits'>
                        <input type="hidden" name="user_id">
                        <div class="form-group">
                            <label>Nick</label>
                            <input type="text" class="form-control" name='nick' disabled="true">
                        </div>
                        <div class="form-group">
                            <label>Credits</label>
                            <input type="number" class="form-control" name='credits' placeholder="30" required="true">
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
    var add_subs = new bootstrap.Modal(document.getElementById('add_subs'));
    var add_credit = new bootstrap.Modal(document.getElementById('add_credit'));
    </script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $(document).ready(function() {
       var table = $('#table2').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "inc/userList.php",
                "columnDefs": [ {
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-primary btn-sm add_c' style='margin-right:5px;'>Add credits</a>  <a href='#' class='btn btn-success btn-sm add_s'>Add Subs</a></br>"+
                "<a href='#' class='btn btn-warning btn-sm make_agency' style='margin-right:5px;margin-top:5px;'>Add Agency</a>  <a href='#' style='margin-top:5px;' class='btn btn-danger btn-sm remove_agency'>Remove Agency</a><a href='#' style='margin-top:5px;margin-left:5px;' class='btn btn-info btn-sm logs'>Logs</a>"
            } ],
            "order": [[ 0, "asc" ]]
        });

        $('#table2').on( 'click', '.add_s', function () {
          var data = table.row( $(this).parents('tr') ).data();
           $('input[name="nick"]').val(data[1]);
           $('input[name="user_id"]').val(data[0]);
           add_subs.show();
        });


        $('#table2').on( 'click', '.add_c', function () {
          var data = table.row( $(this).parents('tr') ).data();
           $('input[name="nick"]').val();
           $('input[name="user_id"]').val(data[0]);
           add_credit.show();
        });

        $('#table2').on( 'click', '.make_agency', function () {
          var data = table.row( $(this).parents('tr') ).data();
            var r = confirm("Are you sure to make an agency "+data[1]);
            if (r == true) {
               window.location.href = "users.php?action=make_agency&id="+data[0];
            } 
        });

        $('#table2').on( 'click', '.remove_agency', function () {
          var data = table.row( $(this).parents('tr') ).data();
            var r = confirm("Are you sure to remove an agency "+data[1]);
            if (r == true) {
               window.location.href = "users.php?action=remove_agency&id="+data[0];
            } 
        });

        $('#table2').on( 'click', '.logs', function () {
          var data = table.row( $(this).parents('tr') ).data();
          window.location.href = "details.php?id="+data[0];
        });

        
    });
    </script>
</body>

</html>