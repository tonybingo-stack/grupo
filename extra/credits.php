<?php

include 'inc/db.php';
$user = check_user();

if (isset($_COOKIE['twp_check'])) {
    $credits =  query("SELECT * FROM `gr_credits` WHERE `app_in_id` != '' ORDER BY `gr_credits`.`price` ASC");
    $twp = true;
} else {
    $credits =  query('SELECT * FROM `gr_credits` ORDER BY `gr_credits`.`price` ASC');
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
        <h2>شراء الكوينز</h2><br />
        <div class="pricing-table">
            <?php foreach ($credits as $key => $value): ?>
            <div class="pricing-box">
                <h2><?php echo $value['name']; ?></h2>
                <span class="price"><?php echo $value['price']; ?>$</span>
                <p class="description">ارسل الهدايا و ادخل الغرف المدفوعة عن طريق الكوينز</p>
                <span class="pricing-table-divider"></span>

               <?php if ($twp): ?>
                    <a class="btn btn_buy" aip_id="<?php echo $value['app_in_id']; ?>" target="_blank" href="#"><b>Pay with google play</b></a>
               <?php else: ?>
                 <a class="btn btn_buy" aip_id="<?php echo $value['app_in_id']; ?>" target="_blank" href="payment/create_payment.php?package=credit&id=<?php echo $value['id']; ?>&time=<?php echo time(); ?>"><b>ادفع</b></a>   
               <?php endif ?>

               
                <span class="pricing-table-divider"></span>
            </div>
            <?php endforeach ?>
             <?php if (count($credits) == 0): ?>
                <div class="pricing-box">
                  <p class="description">We not have active app in purchase product.</p>  
                </div>
            <?php endif ?>
        </div>
    </div>
</body>


<script type="text/javascript">
    
    $('.btn_buy').click(function (e) {
       if ($(this).attr('aip_id')!='') {
        event.stopPropagation();
        parent.aip(1,$(this).attr('aip_id'));
        return false;
       }
    })

</script>


</html>