<?php
include 'inc/admin.php';
$user = check_user();



if (isset($_GET['id']) && isset($_GET['suspend'])) {
    if ($_GET['suspend']==1) {
        query('UPDATE `gr_gifts` SET status=0 WHERE id ='.$_GET['id']);
    } else {
        query('UPDATE `gr_gifts` SET status=1 WHERE id ='.$_GET['id']);
    }
}

if (isset($_FILES['file']['name']) && isset($_POST['name']) && isset($_POST['credit'])) {

    $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if($extension=='jpg' || $extension=='jpeg' || $extension=='png' || $extension=='gif')
    {
        $filename = md5(time()).'.'.$extension;
        $uploadfile = dirname(__FILE__).'/../gifts/'.$filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $name = $_POST['name'];
            $credit = $_POST['credit'];
            $sql = "INSERT INTO `gr_gifts`( `name`, `image`, `credits`) VALUES ('$name','$filename',$credit)";
            query($sql);
        }   else {
            $err =  "Gift File not saved.";
        }
    } else {
        $err =  "Gift File extension not supported.";
    }

}

if (isset($_FILES['file']['name']) && isset($_POST['name']) && isset($_POST['sound'])) {

    $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if($extension=='mp3' || $extension=='wav')
    {
        $filename = md5(time()).'.'.$extension;
        $uploadfile = dirname(__FILE__).'/../sounds/'.$filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $name = $_POST['name'];
            $sql = "INSERT INTO `gr_gift_sound`( `name`, `sound`) VALUES ('$name','$filename')";
            query($sql);
        }   else {
            $err =  "Sound File not saved.";
        }
    } else {
        $err =  "Sound File extension not supported.";
    }

}
if (isset($_POST['sound']) && isset($_POST['soundSet']) && isset($_POST['giftid'])) {
    query('UPDATE `gr_gifts` SET sound='.$_POST['sound'].' WHERE id ='.$_POST['giftid']);
}



$sounds = query('SELECT * FROM `gr_gift_sound` ORDER BY name');


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
						<a class="nav-link active" href="gifts.php" data='gifts'>
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
                    <h1 class="mt-4">Gifts</h1>

                    <?php if (isset($err)): ?>
                    <div class="alert alert-warning" role="alert"><?php echo $err; ?></div>   
                    <?php endif ?>

                    <div class="row">
                       
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                   Gifts <a href="#" class="btn btn-success btn-sm" onclick="add_gift.show();" style="float: right;">Add Gift</a> <a href="#" class="btn btn-primary btn-sm" onclick="add_sound.show();" style="float: right;">Add Sound</a>
                                </div>
                                <div class="card-body">
                                	
                                	 <table class="table">
                                		<thead>
                                			<tr><th style="text-align: center;">Image</th><th>Name</th><th>Credits</th><th>Sound</th><th>Action</th></tr>
                                		</thead>
                                		<tbody>
                                			 <?php 
                                			 $last_orders = gifts();
                                			 foreach ($last_orders as $key => $value): ?>
                                			 	<tr>
                                			 		<td style="text-align: center;"><img src="../gifts/<?php echo $value['image']; ?>" style='height: 100px; width: 100px;'></td>
                                			 		<td><?php echo $value['name']; ?></td>
                                			 		<td><?php echo $value['credits']; ?></td>
                                                    <td><?php 
                                                    if ($value['sound']==0) {
                                                        echo 'Sound Not Set';
                                                    } else {
                                                        foreach ($sounds as $key2 => $value2) {
                                                            if ($value2['id'] == $value['sound']) {
                                                                echo $value2['name'];
                                                            }
                                                        }
                                                    } ?></td>
                                			 		<td>
                                                        <?php if ($value['status']==1): ?>
                                                         <a href="gifts.php?id=<?php echo $value['id']; ?>&suspend=1" class="btn btn-danger btn-sm">Suspend this gift used</a>
                                                        <?php else: ?>
                                                         <a href="gifts.php?id=<?php echo $value['id']; ?>&suspend=0" class="btn btn-success btn-sm">Active this gift used</a>
                                                        <?php endif ?>
                                                        <a href="#" class="btn btn-primary btn-sm setSound" giftId ='<?php echo $value['id']; ?>'>Set Alert Sound</a>
                                                    </td>
                                			 	</tr>
                                			 <?php endforeach ?>
                                			<tr></tr>
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
            <div class="modal fade" id="add_gift" tabindex="-1" aria-labelledby="add_gift" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="package Title">Add new gift</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="gifts.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name='name' required="true">
                                </div>
                                <div class="form-group">
                                    <label>Credit</label>
                                    <input type="number" class="form-control" name='credit' required="true" min="1">
                                </div>
                                <div class="form-group">
                                    <label>File</label>
                                    <input type="file" class="form-control" name='file' required="true" >
                                </div>
                                <button type="submit" class="btn btn-success"  style="margin: 20px auto;">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add_sound" tabindex="-1" aria-labelledby="add_sound" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="package Title">Add new Sound</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="gifts.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="sound" value="1">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name='name' required="true">
                                </div>
                                <div class="form-group">
                                    <label>File</label>
                                    <input type="file" class="form-control" name='file' required="true" >
                                </div>
                                <button type="submit" class="btn btn-success"  style="margin: 20px auto;">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="set_sound" tabindex="-1" aria-labelledby="set_sound" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="package Title">Set Sound</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="gifts.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="soundSet" value="1">
                                <input type="hidden" name="giftid" value="0" id='tGift'>
                               
                                <div class="form-group">
                                    <label>Sounds</label>
                                    <?php foreach ($sounds as $key => $value): ?>
                                     <?php echo $value['name']; ?>:</br><input class="form-check-input" type="radio" name="sound" value="<?php echo $value['id']; ?>">
                                     <audio controls>
                                        <source src="../sounds/<?php echo $value['sound']; ?>">
                                        Your browser does not support the audio element.
                                    </audio>
                                     </br>
                                    <?php endforeach ?>
                                    
                                </div>
                                <button type="submit" class="btn btn-success"  style="margin: 20px auto;">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script type="text/javascript">
                var add_gift = new bootstrap.Modal(document.getElementById('add_gift'));
                var add_sound = new bootstrap.Modal(document.getElementById('add_sound'));
                var set_sound = new bootstrap.Modal(document.getElementById('set_sound'));

                $( "body" ).on( "click", ".setSound", function() {
                    $('#tGift').val($(this).attr('giftId'));
                    set_sound.show();
                });

    </script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
</body>

</html>