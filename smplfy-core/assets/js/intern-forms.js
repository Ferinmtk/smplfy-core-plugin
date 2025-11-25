jQuery(document).ready(function($) {
    console.log("SMPLFY Core intern form script loaded");

    $('input, textarea, select').on('focus', function() {
        $(this).css('background', '#fff6a1');
    }).on('blur', function() {
        $(this).css('background', '');
    });
});
