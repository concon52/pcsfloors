$(document).ready(function () {
    $(document).on('mouseenter', '.effectfront', function () {
        $(this).parent().find(":button").show();

    $('.effectfront').not('')

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


$(document).ready(function(){
	$.cookie.json = true
	// $.cookie("products", [])

	$('.orderbutton').click(function(){
		appendCookie("products", {
			'name' : $('.title-one').text(),
			'productPic' : $('#carouselInner img').prop('src'),
			'quantity' : 1,
			'colorName' : $(this).parent().siblings('p').text(),
			'colorURL' : $(this).parent().find('img').prop('src')
		})


		// console.log('name: ' + name + ' | productPic: ' + productPic + ' | colorName: ' + colorName + ' | colorURL: ' + colorURL)
	});
});



function appendCookie(cookieName, p){
	// cookieName is the name of the cookie ot append to
	// p is a dictionary to append to the 'products' cookie for the cart

	$.cookie.json = true

	products = $.cookie('products')

	if (products){
		products.push(p)
	}
	else{
		products = p
	}

	$.cookie('products', products)
}