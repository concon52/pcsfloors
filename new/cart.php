<?php
include 'header.php';

    // get the array of dictionarys which represent the product samples in our cart
    $productsCookie = json_decode($_COOKIE['products'], true);
    
    $productIDs = array();
    foreach($productsCookie as &$p){
        $productIDs[] = $p['id'];
    }

    // remove duplicate IDs as well as empty elements
    $productIDs = array_filter(array_unique($productIDs));
    $productIDs = implode(',', $productIDs);
    echo $productIDs;

    $dbProducts = array();


    // connect to database
    $mysqli = new mysqli('mysqlcluster9.registeredsite.com','pcsfloors','Padpimp1','padpimp');

    if ($mysqli->connect_error) 
    {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    // $id = $_GET['id'];
    // $query = "SELECT * FROM Products WHERE id = $id";
    $query = "SELECT * FROM Products WHERE id IN ({$productIDs})";
    
    if($result = mysqli_query($mysqli, $query))
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $picarray = json_decode($row['picture']);
            $colorarray = json_decode($row['colors']);
            $manufacturer = $row['manufacturer'];

            $dbProducts[$row['id']] = array(
                'name' => $row['name'],
                'picArray' => json_decode($row['picture'], true),
                'colorArray' => json_decode($row['colors'], true),
                'manufacturer' => $row['manufacturer']
            );
        }

        // var_dump($dbProducts);


    }

?>

<!DOCTYPE html>

<?php outputHeader(); ?>

    <!--/#navigation-->
    <section>
    <div class="container" style="padding-top: 100px;">
        <!-- <section id="products">
            <div class="shopping"> -->

        <form id='cartProducts' action="action_page.php" method='get'>
        <table id="cart" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: center;">Color</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: center;">Remove from cart</th>
                </tr>
            </thead>
            <tbody>
<!--                 <tr>
                    <td class="tableCell productCell" data-th="Product">
                        <div>
                            <div>
                                <img class="productThumb" src="http://www.capriathome.com/images/products/cork_floating_floor/gallery/2.jpg">
                            </div>
                            <div>
                                <h4 class="nomargin">Cork Floating Floor</h4>
                            </div>
                        </div>
                    </td>
                    <td class="tableCell" data-th="Color">
                        <div>
                            <img class="colorThumb" src="http://www.capriathome.com/images/products/cork_floating_floor/thumbs/Chateau_White.jpg">
                        </div>
                        <div>
                            Chateau White
                        </div>
                    </td>
                    <td class="tableCell" data-th="Quantity">
                        <input size="2" style="width:unset;" type="number" class="text-center" value="1">
                    </td>
                    <td class="tableCell" data-th="Remove from cart">
                        <input type="button" class="btn btn-primary" value="Remove">
                    </td>
                </tr> -->


            </tbody>
            <tfoot>
                <tr>
                    <td><a href="productMenu.php" class="btn_-warning"><i class="left"></i> Continue Shopping</a></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td colspan="2" class="hidden-xs"'></td>
                </tr>
            </tfoot>
        </table>
        </form>
        <!-- </div>
        </section> -->
    </div>
    </section>

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h1>Complete your order.</h1>
                    <p>Please enter your contact information to complete your sample order with PCS Distributors.</p>
                        <!-- <form id="contact-form" method="post" action="contactScript.php" role="form"> -->
                        <form id="contact-form" onsubmit="validateMyForm();" method="post" action="contactScript.php" role="form">
                            <div class="messages"></div>

                            <div class="controls">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="form_name">Firstname *</label>
                                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="form_lastname">Lastname *</label>
                                            <input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Please enter your lastname *" required="required" data-error="Lastname is required.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="form_email">Email *</label>
                                            <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="form_phone">Phone</label>
                                            <input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Please enter your phone">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="form_message">Message</label>
                                            <textarea id="form_message" name="message" class="form-control" placeholder="Additional questions, comments, or concerns" rows="4"></textarea> 
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="submit" class="btn btn-success btn-send" value="Place order">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-muted"><strong>*</strong> These fields are required.</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </section>
    <footer id="footer">
        <div class="container">
            <div class="text-center">
                <p>Copyright &copy;
                    <script>
                    document.write(new Date().getFullYear())
                    </script> - PCS Distributors | All Rights Reserved
                </p>
            </div>
        </div>
    </footer>
    <!--/#footer-->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/smoothscroll.js"></script>
    <script type="text/javascript" src="js/jquery.isotope.min.js"></script>
    <script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="js/jquery.parallax.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/cart.js"></script>

    <script>
        function validateMyForm()
        {
            messageHandle = $("#contact-form textarea[name='message']")
            
            temp = messageHandle.val()

            productsString = ''

            $.cookie.json = true;

            productArray = $.cookie("products")

            if(productArray !== undefined && productArray instanceof Array){
                productArray.forEach(function(item, index, array){
                    productsString += '\n' + (index + 1) + '\n\tProduct Name: ' + item['name'] + '\n\tColor Name: ' + item['colorName'] + '\n\tSupplier: ' + item['manufacturer'] + '\n\n'
                });
            }

            message = productsString + temp

            messageHandle.val(message)

            return true
        }

    </script>
    <script>
        <?php //foreach($dbProducts as $key => $value): ?>
        //     // var thisScript = document.currentScript
        //     // $(document).ready(function(){
        //     //     addcarouselitem("<?=$picarray[$key];?>", "<?=$key;?>") 
        //     //     // thisScript.remove()
        //     // })

        //     console.log('key: ' + <?= $key; ?>)


            // insertProductsWithDBInfo(<?= json_encode($dbProducts) ?>)
        <?php //endforeach; ?>

        // showProductInCart(name, productPic, quantity, colorName, colorURL)
    </script>
</body>

</html>




































































