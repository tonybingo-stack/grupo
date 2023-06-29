<?php

include 'inc/db.php';
    $user = check_user();
    $gifts =  query('SELECT * FROM `gr_gifts` WHERE status = 1 ORDER BY `gr_gifts`.`credits` ASC');

?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style type="text/css">
    .gift {
        position: relative;
        display: inline-block;
        margin: 5px 0;
        width: 70px;
        height: 70px;
        text-align: center;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .gift>img {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        transition: opacity .2s, -webkit-filter .2s;
        transition: filter .2s, opacity .2s;
        transition: filter .2s, opacity .2s, -webkit-filter .2s;
    }

    .gradient5 {
        background-color: #21D4FD !important;
        background-image: linear-gradient(19deg, #21D4FD 0%, #B721FF 100%) !important;
    }



    ::-webkit-scrollbar {
        overflow: visible;
        height: 7px;
        width: 7px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, .2);
        background-clip: padding-box;
        border: solid transparent;
        border-width: 0px;
        min-height: 28px;
        padding: 100px 0 0;
        box-shadow: inset 1px 1px 0 rgba(0, 0, 0, .1), inset 0 -1px 0 rgba(0, 0, 0, .07);
    }

    ::-webkit-scrollbar-thumb:active {
        background-color: rgba(0, 0, 0, .4);
    }

    ::-webkit-scrollbar-button {
        height: 0;
        width: 0;
    }

    ::-webkit-scrollbar-track {
        background-clip: padding-box;
        border: solid transparent;
        border-width: 0 0 0 2px;
    }
    </style>
</head>

<body>
    <div class="carousel__scroll">
        <div class="gift gift--hover  send-gift" data-gprice="-1" data-id='0' data-src="https://www.cibc.com/content/dam/global-assets/icons/illustrative/grey-circle/objects/paper-plane-256x256.svg" style="margin-right:10px;height:140px;width:140px;"> <img src="https://www.cibc.com/content/dam/global-assets/icons/illustrative/grey-circle/objects/paper-plane-256x256.svg" width="70" style="border-radius:15px"> <span class="gradient5" style="position:absolute;bottom:10px;left:0;width:70%;margin-left:15%;text-align:center;background:gradient5;color:#ffffff;border-radius:5px"> >1 Credits</span> <span class="sendGiftName" style="position:absolute;top:10px;left:0;width:100%;text-align:center;color:#252323"> Send Credits </span> </div>

        <?php foreach ($gifts as $key => $value): ?>
        <div class="gift gift--hover  send-gift" data-gprice="<?php echo $value['credits'];?>" data-id='<?php echo $value['id'];?>' data-src="https://sabayachat.com/chat/extra/gifts/<?php echo $value['image'];?>" style="margin-right:10px;height:140px;width:140px;"> <img src="https://sabayachat.com/chat/extra/gifts/<?php echo $value['image'];?>" width="70" style="border-radius:15px"> 
            <span class="gradient5" style="position:absolute;bottom:10px;left:0;width:70%;margin-left:15%;text-align:center;background:gradient5;color:#ffffff;border-radius:5px"> <?php echo $value['credits'];?> Credits</span> <span class="sendGiftName" style="position:absolute;top:10px;left:0;width:100%;text-align:center;color:#252323"> <?php echo $value['name'];?> 
            </span> 
        </div>
        <?php endforeach ?>
    </div>
    <script type="text/javascript">
    $('.send-gift').click(function() {

        var price = $(this).attr('data-gprice');
        var image = $(this).attr('data-src');
        var giftId = $(this).attr('data-id');
        parent.send_gift_new(price, image,giftId);

    });
    </script>
</body>

</html>