$('.ticket-img').click(function () {
    code = $(this).attr('code');
    $('#qrcode-url').html('');
    new QRCode(document.getElementById("qrcode-url"), code);
    // $('#qrcode-url').attr('src','/api/qrcode/'+code);
    $('#qrcode-modal').modal();
});