

$(document).ready(function () {
    $(document).on('mouseenter', '.effectfront', function () {
        $(this).parent().find(":button").show();
    }).on('mouseleave', '.effectfront', function () {
        $(this).parent().find(":button").hide();
    });
});

$('.viewbutton').on('click', function() {
   $('.imagepreview').attr('src', $(this).parent().find('img').attr('src')); 
   $('#imagenamefooter').text($(this).parent().parent().find('p').html());
   $('#imagemodal').modal('show');
});
