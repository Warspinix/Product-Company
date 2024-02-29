<?php
    session_start();
?>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="style.css">
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
                            <br><h1>Search Products</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <input type="text" name="product_name" required>
                                    <label for="product_name">Product Name</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Search">
                                </div>
                            </form>
                            <?php
                            if (isset($_POST["product_name"])) {
                                $product_name=$_POST["product_name"];
                                $q2="SELECT product_id, product_name, price
                                    FROM product
                                    WHERE product_name LIKE '%$product_name%'";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)>0) {
                                        echo "
                                        <table>
                                            <tr>
                                                <th>Product ID</th>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                            </tr>";
                                        while ($row2=mysqli_fetch_array($res2)) { 
                                            echo "
                                            <tr>
                                                <td>$row2[product_id]</td>
                                                <td>$row2[product_name]</td>
                                                <td>$row2[price]</td>
                                            </tr>";
                                        }
                                        echo "
                                        </table>";
                                    } else {
                                        echo "No products found.";
                                    }
                                } else {
                                    die("Error: ".mysqli_error($link));
                                }
                            }
                        echo "      
                        </div>
                    </div>
                ";
            } else {
                die("<br>Error: ".mysqli_error($link));
            }
        } else {
            echo "<br><br><div style='text-align:center;'><h1>You aren't logged in.</h1><br>
                    <a href='0_home.html'><button class='edit-button'>Go Home</button></a>&emsp;
                    <a href='0_login.php'><button class='edit-button'>Login</button></a></div><br><br>";
        }
    ?>
    </body>
</html>