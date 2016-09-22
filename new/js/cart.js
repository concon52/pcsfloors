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
            "<tr><td class='tableCell productCell' data-th='Product'><div><div><img class='productThumb' src='" + 
            ((productPic) ? productPic : '') + 
            "'></div><div><h4 class='nomargin'>" + 
            name + 
            "</h4><input type='hidden' name='name' value='" + 
            name + 
            "'></div></div></td><td class='tableCell' data-th='Color'><div><img class='colorThumb' src='" + 
            ((colorURL) ? colorURL : '') + 
            "'></div><div>" + 
            colorName + 
            "</div><input type='hidden' name='colorName' value='" + 
            colorName + 
            "'></td><td class='tableCell' data-th='Quantity'><input size='2' style='width:unset;' type='number' class='text-center' value='" + 
            quantity + 
            "'><input type='hidden' name='quantity' value='" + 
            quantity + 
            "'></td><td class='tableCell' data-th='Remove from cart'><input type='button' class='btn btn-primary removebutton' value='Remove'></td></tr>"
        )

        console.log('name: ' + name + ' | productPic: ' + ((productPic) ? productPic : 'N/A')   + ' | quantity: ' + quantity + ' | colorName: ' + colorName + ' | colorURL: ' + ((colorURL) ? colorURL : 'N/A'))
    // }
}


 
// // Note: ended up needing to requery the DB --for info that we could already have in cookie-- because cookies are pretty small (4kb)
// //   We hit the limit around 12 products stored in cookie the other way so we're passing the min info via cookie and querying the rest from DB
// function insertProductsWithDBInfo(dbProducts)
// {
//     $.cookie.json = true;

//     productArray = $.cookie("products")


//     // $.each(productArray, function(key, pDict){
//     productArray.forEach(function(pDict, i, arr){
//         p = '';
//         // match product info from cookie with product info from DB
//         for(var key in dbProducts){
//         // $.each(dbProducts, function(key, element){
//         // dbProducts.forEach(function(element, index, array){
//             if(key == pDict.id){
//                 p = dbProducts[key];
//                 break;
//             }
//         };



//         // find the color URL based on the name that we stored
//         colorURL = ''

//         if(p){
//             $.each(p.colors, function(key, element){
//             // p.colors.forEach(function(element, index, array){
//                 if(element.name == pDict.c){
//                     colorURL = element.url;
//                     // break;
//                 }
//             });

//             insertProductInCart(p.name, p.productPic, pDict.q, pDict.c, colorURL)
//         }

//         console.log(pDict)
//     });

// 	    // showProductInCart(pDict.name, pDict.productPic, pDict.quantity, pDict.colorName, pDict.colorURL);
// }


function doStuff(){
    $.cookie.json = true;

    productArray = $.cookie("products")

    if(productArray !== undefined && productArray instanceof Array){
        productArray.forEach(function(pDict, index, array){
            console.log(pDict)
            showProductInCart(pDict.name, pDict.productPic, pDict.quantity, pDict.colorName, pDict.colorURL);
        });
    }
}

doStuff()




$('.removebutton').click(function(){
    $(this).parent().parent().remove()
})
