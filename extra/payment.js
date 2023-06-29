function googlePaymentSuccess(orderId,accessToken) {
	  $.ajax({
        url: home_url + 'extra/ajax.php?t=' + Date.now(),
        type: "post",
        data: { action: 'googlePayments',orderId:orderId,  accessToken:  accessToken},
        success: function(response) {
           iziToast.warning({
                title: 'Mobile payment is processing.',
                message: '',
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

var report_messsage = '';
var report_subject = '';
$('#reportUser').click(function (e) {

    var targUser = $(this).parent().find('.name:first').text();

    report_messsage = '';
    report_subject = 1;
    iziToast.question({
        rtl: false,
        layout: 1,
        drag: false,
        timeout: false,
        close: false,
        overlay: true,
        displayMode: 1,
        id: 'question',
        progressBar: true,
        title: 'Report User!',
        message: 'Please indicate the reason for your report.',
        position: 'center',
        inputs: [
            ['<input type="text" placeholder="Your message...">', 'keyup', function (instance, toast, input, e) {
                report_messsage = input.value;
            }, true],
            ['<select><option value="Select">Select subject</option><option value="1">General</option><option value="2">Obscenity</option><option value="3">Pornography</option><option value="4">Racism</option></select>', 'change', function (instance, toast, select, e) {
                report_subject = select.options[select.selectedIndex].value;

            }]
        ],
        buttons: [
            ['<button><b>SEND REPORT</b></button>', function (instance, toast, button, e, inputs) {
               instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
               iziToast.success({
                    zindex: 9999,
                    timeout: 2000,
                    title: 'Thank you for your notification.',
                    overlay: true,
                    message: 'Your complaint will be investigated as soon as possible.',
                    position: 'center'
                });
               sendReportAjax(targUser);

            }, false], 
           ['<button>CANCEL</button>', function (instance, toast, button, e) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

            }]
        ]
    });
});



function sendReportAjax (user) {
    $.ajax({
        url: home_url + 'extra/ajax.php?t=' + Date.now(),
        type: "post",
        data: { action: 'reportUser',report_messsage: report_messsage, report_subject: report_subject,user: user },
        success: function(response) {
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}