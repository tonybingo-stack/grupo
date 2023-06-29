<?php

include 'inc/db.php';
$user = check_user();


if (isset($_COOKIE['twp_check'])) {
    $credits =  query("SELECT * FROM `gr_subs` WHERE `app_in_id` != '' ORDER BY `gr_subs`.`monthly_fee` ASC");
    $twp = true;
} else {
    $credits =  query('SELECT * FROM `gr_subs` ORDER BY `gr_subs`.`monthly_fee` ASC');
    $twp = false;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="assets/prices.css?v=<?php echo time(); ?>" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no shrink-to-fit=no">
    <title>SabayaChat Payment System</title>
    <script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>
</head>

<body translate="no">
    <div class="wrapper">
        <h2>Join Memberships</h2><br />
        <div class="pricing-table">
            <?php foreach ($credits as $key => $value): ?>
            <div class="pricing-box">
                <h2><?php echo $value['name']; ?></h2>
                <span class="price">Mountly: <?php echo $value['monthly_fee']; ?>$<br/>Annual: <?php echo $value['annual_fee']; ?>$</span>
                <p class="description">Enjoy additional privileges by purchasing membership packages..</p>
                <span class="pricing-table-divider"></span>

                <?php if ($twp): ?>
                    <a class="btn btn_buy" aip_id="<?php echo $value['app_in_id']; ?>" href="#"><b>Pay with google play</b></a><br/>
               <?php else: ?>
                  <a class="btn btn_buy" aip_id="<?php echo $value['app_in_id']; ?>" href="payment/create_payment.php?package=subs&id=<?php echo $value['id']; ?>&periot=0&time=<?php echo time(); ?>"><b>Mountly</b></a><br/> 
               <?php endif ?>


                <?php if ($twp == false): ?>
                    <a class="btn btn_buy" aip_id="<?php echo $value['app_in_id']; ?>" href="payment/create_payment.php?package=subs&id=<?php echo $value['id']; ?>&periot=1&time=<?php echo time(); ?>"><b>Annuel</b></a><br/>
                <?php endif ?>
                <span class="pricing-table-divider"></span>
                <ul>
                    <li>New badges.</li>
                    <li>Special greet messages</li>
                    <?php if ($value['free_credit']): ?>
                    <li><?php echo $value['free_credit']; ?> Free Credits</li>   
                    <?php endif ?>
                </ul>
            </div>
            <?php endforeach ?>


             <?php if (count($credits)==0): ?>
                <div class="pricing-box">
                  <p class="description">We not have active app in purchase product.</p>  
                </div>
            <?php endif ?>
        </div>
    </div>
    <script type="text/javascript">
    
    $('.btn_buy').click(function (e) {
       if ($(this).attr('aip_id')!='') {
        event.stopPropagation();
        parent.aip(2,$(this).attr('aip_id'));
        return false;
       }
    })

    </script>
</body>

</html>