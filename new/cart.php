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
                    <td><a href="#" class="btn_-warning"><i class="left"></i> Continue Shopping</a></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td><a href="#" onclick="$('#cartProducts').submit()" class="checkoutk">Place order<i class="-right"></i></a></td>
                </tr>
            </tfoot>
        </table>
        </form>
        <!-- </div>
        </section> -->
    </div>
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




































































