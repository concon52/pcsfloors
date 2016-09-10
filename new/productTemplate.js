$(document).ready(function() {
    $(document).on('mouseenter', '.effectfront', function() {
        $(this).parent().find(":button").show();

        $('.effectfront').not('')

    }).on('mouseleave', '.effectfront', function() {
        $(this).parent().find(":button").hide();
    });
});

$('.viewbutton, .xsviewbutton').on('click', function() {
    $('.imagepreview').attr('src', $(this).parent().find('img').attr('src'));
    $('#imagenamefooter').text($(this).parent().parent().find('p').html());
    $('#imagemodal').modal('show');
});

function addcarouselitem(imgsrc, index) {
    scriptSelector = "#script" + index
    console.log(scriptSelector)
    $(scriptSelector).remove()

    console.log('imgsrc: ' + imgsrc)
    console.log('index: ' + index)

    var activeClass = (index == 0) ? 'active' : ''

    $('#carouselList').append('<li data-target="#main-carousel" data-slide-to="' + index + '" class="' + activeClass + '"></li>')
    $('#carouselInner').append('<div class="item ' + activeClass + '" ><img src="' + imgsrc + '"></div>')
}


$(document).ready(function() {
    $.cookie.json = true
        // $.cookie("products", [])


	urlParams = window.location.href.split('?')[1].split('&')
	
	id = ''
	// r = /^id\=[0-9]$/
	
	// grab the value of the 'id' get param in the url
	urlParams.forEach(function(element, index, array){
		if(RegExp("^id\=[0-9]+$").test(element)){
			id = element.split('=')[1]
		}
	});

	console.log('id: ' + id)

    $('.container .addSampleToCart').click(function() {
    	// p = {
    	// 	'id' : id,
    	// 	'q' : 1,
    	// 	'c' : $(this).parent().siblings('p').text()
    	// }
        p = {
        	// parts that are found with the respect to the overall page
            'name': $('.title-one').text(),
            'productPic': $('#carouselInner img').prop('src'),
            'quantity': 1,
            'manufacturer': $('#manufacturer').text(),

            // parts that are found with respect to the current color node
            'colorName': $(this).parent().siblings('p').text(),
            'colorURL': $(this).parent().find('img').prop('src')
        }

        // console.log('name: ' + p.name + ' | productPic: ' + p.productPic + ' | colorName: ' + p.colorName + ' | colorURL: ' + p.colorURL + ' | manufacturer: ' + p.manufacturer)

        appendCookie("products", p)

    });

    $('.modal-dialog .addSampleToCart').click(function() {
        // p = {
        // 	// parts that are found with respect to the overall page
        // 	'name' : $('.title-one').text(),
        // 	'productPic' : $('#carouselInner img').prop('src'),
        // 	'quantity' : 1,
        // 	'manufacturer': $('#manufacturer').text(),

        // 	// parts that are found with respoect to the current image modal / lightbox thing
        // 	'colorName' : $(this).parent().find('#imagenamefooter').text(),
        // 	'colorURL' : $(this).parent().siblings('.modal-body').find('img').prop('src')
        // }

        // console.log('name: ' + p.name + ' | productPic: ' + p.productPic + ' | colorName: ' + p.colorName + ' | colorURL: ' + p.colorURL + ' | manufacturer: ' + p.manufacturer)

		p = {
			// 'id' : id,
			// 'q' : 1,
			// 'c' : $(this).parent().find('#imagenamefooter').text()

        	// parts that are found with respect to the overall page
        	'name' : $('.title-one').text(),
        	'productPic' : $('#carouselInner img').prop('src'),
        	'quantity' : 1,
        	'manufacturer': $('#manufacturer').text(),

        	// parts that are found with respoect to the current image modal / lightbox thing
        	'colorName' : $(this).parent().find('#imagenamefooter').text(),
        	'colorURL' : $(this).parent().siblings('.modal-body').find('img').prop('src')
        }

        // console.log('name: ' + p.name + ' | productPic: ' + p.productPic + ' | colorName: ' + p.colorName + ' | colorURL: ' + p.colorURL + ' | manufacturer: ' + p.manufacturer)
		
		console.log(p)

		appendCookie("products", p)
    });
});



function appendCookie(cookieName, p) {
    // cookieName is the name of the cookie ot append to
    // p is a dictionary to append to the 'products' cookie for the cart

    $.cookie.json = true

    tempCookie = $.cookie(cookieName)

    if (tempCookie) {
        tempCookie.push(p)
    } else {
        tempCookie = p
    }

    $.cookie(cookieName, tempCookie)
}
