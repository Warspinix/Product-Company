<?php
    session_start();
?>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="style.css">
        <style>
            .field {
                display: inline-block;
                margin-right: 20px;
            }
        </style>
    </head>
    <body>
    <?php
        if(isset($_SESSION["id"])) {
            $link = mysqli_connect("localhost","root","","product_company");
            if ($link == FALSE)
                die("<br><br>Error connecting to database. Please try again later.");
            $q1 = "SELECT b_name, b_country 
                    FROM company
                    WHERE branch_id=$_SESSION[branch_id]";
            if ($res1 = mysqli_query($link, $q1)) {
                $row1 = mysqli_fetch_array($res1);
                $name = $row1["b_name"];
                $country = $row1["b_country"];
                echo "<div class='container'>
                        <div class='left'>
                            <span style='font-size:15px; margin-top: 13px;'>$name, $country</span>
                            <ul><br>
                                <li><a href='0a_branches.php'>Branches</a></li>
                                <li><a href='0b_spares_and_suppliers.php'>Spares and Suppliers</a></li>
                                <li><a href='0c_projects.php'>Projects</a></li>
                                <li><a href='0d_products.php'>Products</a></li>
                                <li><a href='0e_dealer.php'>Dealers</a></li>
                            </ul>";                           
                            echo "
                            <div class='profile-section'>
                                <br>
                                <div class='username'>
                                    $_SESSION[fname] $_SESSION[lname]
                                </div>
                                    <a href='0_view_profile.php'><button class='edit-button'>View Profile</button></a>
                                    <a href='0_logout.php'><button class='logout'>Logout</button></a><br>
                            </div>
                        </div>
                        <div class='main'>    
                            <div class=top>                            
                                <ul>
                                    <li><a href='0d1_add_product.php'>
                                        Add Product
                                    </a></li>                            
                                    <li><a href='0d2_search_products.php'>
                                        Search Products
                                    </a></li> 
                                    <li><a href='0d3_update_product_details.php'>
                                        Update Product Details
                                    </a></li>     
                                    <li><a href='0d4_add_product_to_project.php'>
                                        Add Product to Project
                                    </a></li>                                                                                                                                         
                                </ul>  
                            </div>";
                            ?>
                            <br><h1>Add Product</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <select name="id" required>
                                        <option value="" disable select>Product Type</option>
                                        <option value="111000">iPhone</option>
                                        <option value="112000">iPad</option>
                                        <option value="113000">MacBook</option>
                                        <option value="114000">Apple Watch</option>
                                        <option value="115000">HomePod</option>
                                        <option value="116000">AirPods</option>
                                        <option value="117000">Mac</option>
                                        <option value="118000">Apple Pencil</option>
                                        <option value="119000">Other Accessories</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="text" name="product_name" required>
                                    <label for="product_name">Product Name</label>
                                </div>
                                <br>
                                <div class="field" style="width: 42%;">
                                    <input type="text" name="specifications" required>
                                    <label for="specifications">Specifications</label>
                                </div>
                                <br><br>
                                <div class="field">
                                    <input type="number" name="price" required>
                                    <label for="price">Price</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="warranty" required>
                                    <label for="warranty">Warranty</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Add Product">
                                </div>
                            </form>
                        <?php
                            if (isset($_POST["warranty"])) {
                                $id=$_POST["id"];
                                $product_name=$_POST["product_name"];
                                $specifications=$_POST["specifications"];
                                $price=$_POST["price"];
                                $warranty=$_POST["warranty"];
                                $q2="SELECT MAX(product_id) as product_id
                                FROM product
                                WHERE product_id-$id<100";
                                if ($res2=mysqli_query($link, $q2)) {
                                    $row2=mysqli_fetch_array($res2);
                                    $product_id=$row2["product_id"]+1;
                                    $q3="INSERT INTO product VALUES
                                        ($product_id, '$product_name', '$specifications', $price, '$warranty')";
                                    if (mysqli_query($link, $q3)) {
                                        echo "Product added.";
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                }
                            }
                        echo "      
                        </div>
                    </div>
                ";
            } else {
                die("Error: ".mysqli_error($link));
            }
        } else {
            echo "<br><br><div style='text-align:center;'><h1>You aren't logged in.</h1><br>
                    <a href='0_home.html'><button class='edit-button'>Go Home</button></a>&emsp;
                    <a href='0_login.php'><button class='edit-button'>Login</button></a></div><br><br>";
        }
    ?>
    </body>
</html>