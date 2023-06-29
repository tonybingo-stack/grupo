<?php
include 'inc/admin.php';
$user = check_user();
$stats = today_reports();


define('IMAGEPATH', '../backgrounds/');
foreach(glob(IMAGEPATH.'*') as $filename){
    $imag[] =  basename($filename);
}


if (isset($_POST['action']) && $_POST['action']=='style' && isset($_POST['id']) && isset($_POST['color'])&& isset($_POST['background'])) {
    if (intval($_POST['id'])>0) {
        $chn = $_POST['id'];
        $color = $_POST['color'];
        $bgColor = $_POST['background'];
        $sql = "INSERT INTO `gr_channels_styles`(`channel`, `color`, `background`) VALUES ($chn,'$color','$bgColor') ON DUPLICATE KEY UPDATE color='$color', background = '$bgColor' ";
        query ($sql);
    }

    
}

function _getServerLoadLinuxData()
    {
        if (is_readable("/proc/stat"))
        {
            $stats = @file_get_contents("/proc/stat");

            if ($stats !== false)
            {
                // Remove double spaces to make it easier to extract values with explode()
                $stats = preg_replace("/[[:blank:]]+/", " ", $stats);

                // Separate lines
                $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                $stats = explode("\n", $stats);

                // Separate values and find line for main CPU load
                foreach ($stats as $statLine)
                {
                    $statLineData = explode(" ", trim($statLine));

                    // Found!
                    if
                    (
                        (count($statLineData) >= 5) &&
                        ($statLineData[0] == "cpu")
                    )
                    {
                        return array(
                            $statLineData[1],
                            $statLineData[2],
                            $statLineData[3],
                            $statLineData[4],
                        );
                    }
                }
            }
        }

        return null;
    }

    // Returns server load in percent (just number, without percent sign)
    function getServerLoad()
    {
        $load = null;

        if (stristr(PHP_OS, "win"))
        {
            $cmd = "wmic cpu get loadpercentage /all";
            @exec($cmd, $output);

            if ($output)
            {
                foreach ($output as $line)
                {
                    if ($line && preg_match("/^[0-9]+\$/", $line))
                    {
                        $load = $line;
                        break;
                    }
                }
            }
        }
        else
        {
            if (is_readable("/proc/stat"))
            {
                // Collect 2 samples - each with 1 second period
                // See: https://de.wikipedia.org/wiki/Load#Der_Load_Average_auf_Unix-Systemen
                $statData1 = _getServerLoadLinuxData();
                sleep(1);
                $statData2 = _getServerLoadLinuxData();

                if
                (
                    (!is_null($statData1)) &&
                    (!is_null($statData2))
                )
                {
                    // Get difference
                    $statData2[0] -= $statData1[0];
                    $statData2[1] -= $statData1[1];
                    $statData2[2] -= $statData1[2];
                    $statData2[3] -= $statData1[3];

                    // Sum up the 4 values for User, Nice, System and Idle and calculate
                    // the percentage of idle time (which is part of the 4 values!)
                    $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];

                    // Invert percentage to get CPU time, not idle time
                    $load = 100 - ($statData2[3] * 100 / $cpuTime);
                }
            }
        }

        return $load;
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
                        <a class="nav-link active" href="index.php" data='dashboard'>
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
                    <h1 class="mt-4">Dashboard</h1>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Today Registered</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <h3>
                                        <?php echo $stats['reg'][0]['total']; ?> Users</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-dark text-white mb-4">
                                <div class="card-body">Today Visits</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <h3>
                                        <?php echo $stats['total_visit'][0]['total']; ?> Visit</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Today Orders</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <h3>
                                        <?php echo $stats['orders'][0]['total']; ?> Orders</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Today Used Credits</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <h3>
                                        <?php echo $stats['used'][0]['total']; ?> Visit</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12" style="margin-bottom: 10px;">
                        <a href="allUsed.php" class="btn btn-primary btn-sm" > All used credit logs</a>
                        <a href="fixTable.php" class="btn btn-warning btn-sm"> Fix Sql Tables</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Last Orders
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Method</th>
                                                <th>Products</th>
                                                <th>Price</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                             $last_orders = last_orders();
                                             foreach ($last_orders as $key => $value): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $value['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['method']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['details']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['prices']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['action_time']; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    switch ($value['status']) {
                                                        case 1:
                                                            echo 'Paid';
                                                            break;
                                                        case -2:
                                                            echo 'Canceled by user';
                                                            break;
                                                        default:
                                                            echo 'Not paid';
                                                            break;
                                                    }

                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    System Performance
                                </div>
                                <div class="card-body">
                                    CPU Using:
                                    <?php 
                                      $cpuLoad = getServerLoad();
                                        if (is_null($cpuLoad)) {
                                            echo "CPU load not estimateable (maybe too old Windows or missing rights at Linux or Windows)";
                                        }
                                        else {
                                            echo $cpuLoad . "%";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Last Actions for Credit Used
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Action</th>
                                                <th>Credit</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                             $last_actions = last_actions();
                                             foreach ($last_actions as $key => $value): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $value['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['details']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['credit']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['used_time']; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <h2>Channels Options</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                     <th>Channel</th>
                                     <th>Color</th>
                                     <th>Background Image</th>
                                     <th>User Actions</th>
                                     <th>Action</th>    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $last_actions = query("SELECT v1,id FROM `gr_options` WHERE `type` = 'group'");
                                        foreach ($last_actions as $key => $value) { 
                                        $clr = '';
                                        $bgclr = '';
                                        $styles = query("SELECT * FROM `gr_channels_styles` WHERE channel = ".$value['id']);

                                        if (count($styles)>0) {
                                            $clr = $styles[0]['color'];
                                            $bgclr = $styles[0]['background'];
                                        }

                                        ?>
                                    <tr>
                                        <td>
                                            <?php echo $value['v1']; ?>
                                        </td>
                                        <td>
                                            <input type="color" name="color" value="<?php echo $clr; ?>">
                                        </td>
                                        <td>
                                            <select name='background'>
                                                <option value='0'>Default</option>
                                                <?php foreach ($imag as $key2 => $value2): ?>
                                                    <?php if ($value2 == $bgclr): ?>
                                                        <option value='<?php echo $value2 ?>' selected><?php echo $value2 ?></option>
                                                    <?php else: ?>
                                                        <option value='<?php echo $value2 ?>'><?php echo $value2 ?></option>
                                                    <?php endif ?>
                                                    
                                                <?php endforeach ?>
                                            </select>
                                        </td>
                                        <td>
                                            <a href="channelUsers.php?id=<?php echo $value['id']; ?>&channel=<?php echo urlencode($value['v1']); ?>">Channel User </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm save_style" myid=' <?php echo $value['id']; ?>'>Save Styles</button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <script type="text/javascript">
                $( "body" ).on( "click", ".save_style", function() {
                  var id =  $(this).attr('myid');
                  var parent = $(this).closest('tr');
                  var color = parent.find("input[name='color']:first").val();
                  var background = parent.find("select[name='background']:first").find(":selected").val();
                  console.log(background);
                   $.ajax({
                       type: "POST",
                       url: window.location.href,
                       data: {action:'style',id:id, color:color, background: background}, 
                       success: function(data)
                       {
                           location.reload();
                       }
                    });
                });
                                
            </script>
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
</body>

</html>