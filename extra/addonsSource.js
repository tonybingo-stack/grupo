var default_coin = 0;
var giftTarChannel = 0;
var def_lang = 'en';

 
$.i18n().load({
        en: home_url + 'extra/langs/en.json?t=' + Date.now(),
        ar: home_url + 'extra/langs/ar.json?t=' + Date.now()
    }).always( function () {
        start_load();
    });


function start_load () {

    if (prlang=="614") {
        $.i18n({ locale: 'ar' });
    }

    $("<link/>", {
        rel: "stylesheet",
        type: "text/css",
        href: home_url + 'extra/assets/css/iziToast.min.css'
    }).appendTo("head");

    $("<link/>", {
        rel: "stylesheet",
        type: "text/css",
        href: home_url + 'extra/assets/css/iziModal.min.css'
    }).appendTo("head");

    $("<link/>", {
        rel: "stylesheet",
        type: "text/css",
        href: home_url + 'extra/assets/css/extra.css'
    }).appendTo("head");


    $.getScript(home_url + 'extra/assets/js/iziToast.min.js');

    $('body').append(`
   	<div id="log-modal">
    <table class="table table-dark" style='width:100%;height:450px;overflow: auto;'>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">`+$.i18n('details')+`</th>
          <th scope="col">`+$.i18n('time')+`</th>
        </tr>
      </thead>
      <tbody id='logs_body'>
        
      </tbody>
    </table>
    </div> 
	<div id="credit-iframe"></div> 
    <div id="subs-iframe"></div> 
    <div id="agency-iframe"></div> 
	<div id="gift-iframe"></div>`);

    if ($( window ).width() > 991) {
        $('.swr-grupo .rside .right:first').append(
            `<span id="credit"><img src="` + home_url + `extra/assets/img/coin_icon.png"> <span id="credit_total">0</span></span>`
        );
    } else {
        
        $('.swr-grupo .aside > .head > .icons > i.udolist').prepend(
            `<span id="credit"><img src="` + home_url + `extra/assets/img/coin_icon.png"> <span id="credit_total">0</span></span>`
        );
        $('.swr-grupo .aside > .head > .logo').css('marginRight','-95px');
    }

    

    $('.swr-grupo .panel > .head > .right .searchmsgs:first').before(
        `<i class="gi-gift sendgif" id='send_gift'></i>`
    );

    loadCredits();

    setInterval(function(){ loadCredits(); }, 3000);

    

     $.ajax({
        url: home_url + 'extra/ajax.php?t=' + Date.now(),
        type: "post",
        data: { action: 'checkAccess' },
        success: function(response) {
            if (response==2) {
                $('.swr-menu > ul > li[data-do="logout"]').before(`
                    <li><a href='`+home_url+`extra/admin/' target='_blank' style='color:white;'>Extra Admin Panel</a></li>
                `);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });

     $.ajax({
        url: home_url + 'extra/ajax.php?t=' + Date.now(),
        type: "post",
        data: { action: 'checkAgency' },
        success: function(response) {
            if (response==1) {
                $('.swr-menu > ul > li[data-do="logout"]').before(`
                    <li><a href='#' onclick='agencyAction();' style='color:white;'>`+$.i18n('agency_menu')+`</a></li>
                `);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });


     $('.swr-grupo .lside > .tabs li[act="lastSenders"]').text($.i18n('today'));
     $('.swr-grupo .lside > .tabs li[act="mostPopuler"]').text($.i18n('famous'));

     $.ajax({
        url: home_url + 'extra/ajax.php?t=' + Date.now(),
        type: "post",
        data: { action: 'checkMemberships' },
        success: function(response) {
           if (response==0) {
            $('#my_memberships').text($.i18n('free_member'));
           } else {
            $('#my_memberships').text(subsNames[response]+$.i18n('member'));
           }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });


  $('.swr-menu > ul > li[data-do="logout"]').before(`
        <li class="ajx" id='creditsLog'>`+$.i18n('credits_history')+`</li>
        <li class="ajx" id="ordersLog">`+$.i18n('orders_history')+`</li>
    `);

    $.getScript(home_url + 'extra/assets/js/iziModal.js', function() {

        var isFull = false;

        if ($(window).width() < 645) {
            isFull = true;
        }

        $("#agency-iframe").iziModal({
            headerColor: '#000',
            icon: 'icon-settings_system_daydream',
            overlayClose: true,
            iframe: true,
            title: $.i18n('agency_menu'),
            iframeURL: home_url + 'extra/agency.php',
            fullscreen: true,
            openFullscreen: true,
            borderBottom: false,
            group: 'grupo1',
            onFullscreen: function(modal) {
                console.log(modal.isFullscreen);
            }
        });


        $("#credits-iframe").iziModal({
            headerColor: '#000',
            icon: 'icon-settings_system_daydream',
            overlayClose: true,
            iframe: true,
            title: $.i18n('load_new'),
            iframeURL: home_url + 'extra/credits.php',
            fullscreen: false,
            width: 645,
            openFullscreen: isFull,
            borderBottom: false,
            group: 'grupo1',
            onFullscreen: function(modal) {
                console.log(modal.isFullscreen);
            }
        });

        $("#subs-iframe").iziModal({
            headerColor: '#000',
            icon: 'icon-settings_system_daydream',
            overlayClose: true,
            iframe: true,
            title: $.i18n('memberships_title'),
            iframeURL: home_url + 'extra/subs.php',
            fullscreen: false,
            width: 645,
            openFullscreen: isFull,
            borderBottom: false,
            group: 'grupo1',
            onFullscreen: function(modal) {
                console.log(modal.isFullscreen);
            }
        });

        $("#log-modal").iziModal({
            headerColor: '#000',
            background: 'black',
            icon: 'icon-settings_system_daydream',
            overlayClose: true,
            fullscreen: true,
            openFullscreen: isFull,
            borderBottom: false,
            group: 'grupo2'
        });


        $("#gift-iframe").iziModal({
            headerColor: '#000',
            icon: 'icon-settings_system_daydream',
            overlayClose: true,
            iframe: true,
            iframeURL: home_url + 'extra/gift.php',
            fullscreen: false,
            width: 645,
            openFullscreen: isFull,
            borderBottom: false,
            group: 'grupo4',
            onFullscreen: function(modal) {
                console.log(modal.isFullscreen);
            }
        });


    });

    $('#my_memberships').on('click', function(e) {
        $("#subs-iframe").iziModal('open', event);
    });


    $('#credit').on('click', function(e) {
        $("#credits-iframe").iziModal('open', event);
    });



    $('#send_gift').on('click', function(e) {
        gift_target = $('.swr-grupo .panel').attr('no');
        gift_target_name = getActive();

        $("#gift-iframe").iziModal('setTitle', $.i18n('send_gift_to_nick',gift_target_name));
        $("#gift-iframe").iziModal('open', event);
    });


    $("body").on("click", "#creditsLog", function(event) {
    	  event.stopPropagation();

          $.ajax({
                url: home_url + 'extra/ajax.php?t=' + Date.now(),
                type: "post",
                dataType: "json",
                data: { action: 'creditsLog'},
                success: function(response) {
                $('#logs_body').html('');
                  $.each(response, function( index, value ) {
                      $('#logs_body').append(`<tr>
                        <td>`+value.id+`</td>
                        <td>`+value.details+`</td>
                        <td>`+value.used_time+`</td>
                        </tr>`);
                  });
                  $("#log-modal").iziModal('setTitle', $.i18n('history_credits'));
                  $("#log-modal").iziModal('open');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
          

          
    });

	$("body").on("click", "#ordersLog", function(event) {
    	  event.stopPropagation();

          $.ajax({
                url: home_url + 'extra/ajax.php?t=' + Date.now(),
                type: "post",
                dataType: "json",
                data: { action: 'ordersLog'},
                success: function(response) {
                  $('#logs_body').html('');
                  $.each(response, function( index, value ) {
                      $('#logs_body').append(`<tr>
                        <td>`+value.id+`</td>
                        <td>`+value.order_details+' price:'+value.prices+`$</td>
                        <td>`+value.action_time+`</td>
                        </tr>`);
                  });

                  $("#log-modal").iziModal('setTitle', $.i18n('history_orders'));
                  $("#log-modal").iziModal('open');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });


    });


    $("body").on("click", ".vwgift", function(e) {
        gift_target_name = $(this).parent().parent().parent().parent().find('.center:first b').text();
        gift_target = $(this).attr('no');
        $("#gift-iframe").iziModal('setTitle', $.i18n('send_gift_to_nick',gift_target_name));
        $("#gift-iframe").iziModal('open', event);

    });


    

};

function loadCredits() {
    $.ajax({
        url: home_url + 'extra/ajax.php?t=' + Date.now(),
        type: "post",
        data: { action: 'getCredit' },
        success: function(response) {
            default_coin = parseInt(response);
            $('#credit_total').text(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function getActive() {

    return $(".swr-grupo .panel > .head > .left > span > span:first")
        .clone() //clone the element
        .children() //select all the children
        .remove() //remove all the children
        .end() //again go back to selected element
        .text();


}


function confirm_send_credit(credit) {
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        title: $.i18n('confirm'),
        message: $.i18n('credit_msg',gift_target_name,credit),
        position: 'center',
        buttons: [
            ['<button><b>'+$.i18n('send')+'</b></button>', function(instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                $.ajax({
                    url: home_url + 'extra/ajax.php?t=' + Date.now(),
                    type: "post",
                    dataType: "json",
                    data: { action: 'sendCredit', target: gift_target, credits: credit, giftTarChannel: giftTarChannel },
                    success: function(response) {
                  	if (response.err) {
                    		iziToast.warning({
							    title: 'Caution',
							    message: eval(response.err),
							});
                    	}
                    	if (response.ok) {
                    		credit_message(response.ok);
							loadCredits();
                    	}
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });



            }, true],
            ['<button>'+$.i18n('cancel')+'</button>', function(instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

            }],
        ]
    })
}



var gift_target = 0;
var gift_target_name = '';


function send_gift_new(price, image, giftId) {

    $("#gift-iframe").iziModal('close', event);


    if (price == -1) {

        iziToast.info({
            timeout: 20000,
            overlay: true,
            displayMode: 'once',
            id: 'inputs',
            zindex: 999,
            title: $.i18n('title_send_credit'),
            message: $.i18n('credit_amount'),
            position: 'center',
            drag: false,
            inputs: [
                ['<input type="number">', 'keydown', function(instance, toast, input, e) {
                    console.info(input.value);
                }]
            ],
            buttons: [
                ['<button><b>'+$.i18n('send')+'</b></button>', function(instance, toast, button, e, inputs) {

                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    if (parseInt(inputs[0].value) > 0) {

                        if (parseInt(inputs[0].value) > default_coin) {

                            iziToast.warning({
                                title: $.i18n('title_need_credit'),
                                message: $.i18n('need_credit_msg')
                            });

                            $("#credits-iframe").iziModal('open', event);
                        } else {
                            confirm_send_credit(inputs[0].value);
                        }

                    } else {
                        iziToast.warning({
                            title: $.i18n('info'),
                            message: $.i18n('valid_credits'),
                        });
                    }


                }, false],
                ['<button>'+$.i18n('cancel')+'</button>', function(instance, toast, button, e) {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                }]
            ]
        });

        return;
    }


    if (price > default_coin) {

        iziToast.warning({
            title: $.i18n('title_need_credit'),
            message: $.i18n('need_credit_msg')
        });

        $("#credits-iframe").iziModal('open', event);
    } else {

        iziToast.question({
            timeout: 20000,
            close: false,
            overlay: true,
            displayMode: 'once',
            id: 'question',
            zindex: 999,
            title: $.i18n('confirm'),
            message: $.i18n('gift_msg',gift_target_name,price),
            position: 'center',
            buttons: [
                ['<button><b>'+$.i18n('send')+'</b></button>', function(instance, toast) {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                    $.ajax({
                        url: home_url + 'extra/ajax.php?t=' + Date.now(),
                        type: "post",
                        dataType: "json",

                        data: { action: 'sendGift', target: gift_target, giftId: giftId, giftTarChannel: giftTarChannel },
                        success: function(response) {
                        	if (response.err) {
                        		iziToast.warning({
                                    title: $.i18n('caution'),
								    message: eval(response.err),
								});
                        	}
                        	if (response.ok) {
                        		gift_message(response.ok);
								loadCredits();
                        	}
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });



                }, true],
                ['<button>'+$.i18n('cancel')+'</button>', function(instance, toast) {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                }],
            ]
        })

    }


    console.log(price, image);
}


function gift_message(msg) {
    var argv = msg.split(' ');
    say($.i18n('gift_complate',argv[0],argv[1]));
}

function credit_message(msg) {
    var argv = msg.split(' ');
    say($.i18n('credit_complate',argv[0],argv[1]));
}


function agencyAction () {

    $("#agency-iframe").iziModal('open');
    return false;
}





function aip(proc,sku) {

    if (proc==2){
        try {
        Android.buySUBS(sku);
        }
        catch (error) {
            iziToast.warning({
                title: 'Mobile payment problem',
                message: error,
            });
          console.error(error);
        }
        $("#subs-iframe").iziModal('close');
    }
    else{
        try {
        Android.buyITEM(sku);
        } catch (error) {
            iziToast.warning({
                title: 'Mobile payment problem',
                message: error,
            });
          console.error(error);
        }
        $("#credits-iframe").iziModal('close');
    }

}