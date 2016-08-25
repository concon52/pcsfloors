

$(document).ready(function () {
    $(document).on('mouseenter', '.effectfront', function () {
        $(this).parent().find(":button").show();
    }).on('mouseleave', '.effectfront', function () {
        $(this).parent().find(":button").hide();
    });
});

$('.viewbutton, .xsviewbutton').on('click', function() {
   $('.imagepreview').attr('src', $(this).parent().find('img').attr('src')); 
   $('#imagenamefooter').text($(this).parent().parent().find('p').html());
   $('#imagemodal').modal('show');
});

function addcarouselitem(imgsrc, index)
{
	scriptSelector = "#script" + index
	console.log(scriptSelector)
	$(scriptSelector).remove()

	console.log('imgsrc: ' + imgsrc)
	console.log('index: ' + index)

	var activeClass = (index == 0) ? 'active' : ''

	$('#carouselList').append('<li data-target="#main-carousel" data-slide-to="' + index + '" class="' + activeClass + '"></li>')
	$('#carouselInner').append('<div class="item ' + activeClass + '" ><img src="' + imgsrc + '"></div>')
}
