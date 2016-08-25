function showProductInCart(name, productPic, quantity, colorName, colorURL){

    // if productPic == undefined or productPic == 'undefined'{
    //     $('tbody').append(
    //         "lulz<tr><td class='tableCell productCell' data-th='Product'><div><div></div><div><h4 class='nomargin'>" + 
    //         name + 
    //         "</h4></div></div></td><td class='tableCell' data-th='Color'><div><img class='colorThumb' src='" + 
    //         colorURL + 
    //         "'></div><div>" + 
    //         colorName + 
    //         "</div></td><td class='tableCell' data-th='Quantity'><input size='2' style='width:unset;' type='number' class='text-center' value='" + 
    //         quantity + 
    //         "'></td><td class='tableCell' data-th='Remove from cart'><input type='button' class='btn btn-primary' value='Remove'></td></tr>"
    //     )
    // }
    // else{
        $('tbody').append(
        	"lulz<tr><td class='tableCell productCell' data-th='Product'><div><div><img class='productThumb' src='" + 
        	productPic + 
        	"'></div><div><h4 class='nomargin'>" + 
        	name + 
        	"</h4></div></div></td><td class='tableCell' data-th='Color'><div><img class='colorThumb' src='" + 
        	colorURL + 
        	"'></div><div>" + 
        	colorName + 
        	"</div></td><td class='tableCell' data-th='Quantity'><input size='2' style='width:unset;' type='number' class='text-center' value='" + 
        	quantity + 
        	"'></td><td class='tableCell' data-th='Remove from cart'><input type='button' class='btn btn-primary' value='Remove'></td></tr>"
    	)
    // }
}

    $.cookie.json = true;

    productArray = $.cookie("products")



    for (pDict of productArray){
	    showProductInCart(pDict.name, pDict.productPic, pDict.quantity, pDict.colorName, pDict.colorURL);
    }


