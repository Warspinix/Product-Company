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
                        <div class='left'><br>
                            <span style='font-size:15px; margin-top: 13px;'>$name, $country</span>";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul><br>
                                        <li><a href='4a_products.php'>Products</a></li>
                                        <li><a href='4b_transports.php'>Transports</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='4a_products.php'>Products</a></li>
                                        <li><a href='4b_transports.php'>Transports</a></li>
                                        <li><a href='4c_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='4a_products.php'>Products</a></li>
                                        <li><a href='4b_transports.php'>Transports</a></li>
                                        <li><a href='4c_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            }
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
                        <div class='top'>";
                        if ($_SESSION["position"]=="Regular") {
                            echo "<ul>
                                <li><a href='4b1_view_showrooms.php'>
                                    View Showrooms
                                </a></li>
                                <li><a href='4b2_view_dealers.php'>
                                    View Dealers
                                </a></li>
                                <li><a href='4b3_transport_products.php'>
                                    Transport Products
                                </a></li>                                                           
                            </ul>";
                        } else if ($_SESSION["position"]=="Manager") {
                            echo "<ul>
                                <li><a href='4b1_view_showrooms.php'>
                                    View Showrooms
                                </a></li>
                                <li><a href='4b2_view_dealers.php'>
                                    View Dealers
                                </a></li>
                                <li><a href='4b3_transport_products.php'>
                                    Transport Products
                                </a></li>  
                                <li><a href='4b4_view_transports.php'>
                                    View Outgoing Product Transports
                                </a></li>                               
                            </ul>";
                        } else {
                            echo "<ul>
                                <li><a href='4b1_view_showrooms.php'>
                                    View Showrooms
                                </a></li>
                                <li><a href='4b2_view_dealers.php'>
                                    View Dealers
                                </a></li>
                                <li><a href='4b3_transport_products.php'>
                                    Transport Products
                                </a></li>
                                <li><a href='4b4_view_transports.php'>
                                    View Outgoing Product Transports
                                </a></li>                                 
                            </ul>";
                        }  
                    echo "
                        </div>";
                        ?>
                        <br><h1>Transport Products</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <select name="destination" required>
                                    <option value="" disable select>Showroom or Dealer</option>
                                    <option value="showroom">Showroom</option>
                                    <option value="dealer">Dealer</option>
                                </select>
                            </div>
                            <div class="field">
                                <input type="number" name="destination_id" required>
                                <label for="destination_id">Destination ID</label>
                            </div>
                            <div class="field">
                                <input type="number" name="product_id" required>
                                <label for="product_id">Product ID</label>
                            </div>
                            <div class="field">
                                <input type="number" name="quantity" min=1 required>
                                <label for="quantity">Quantity</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Schedule Transport">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["quantity"])) {
                                $destination=$_POST["destination"];
                                $destination_id=$_POST["destination_id"];
                                $product_id=$_POST["product_id"];
                                $quantity=$_POST["quantity"];
                                $send_date=date("Y-m-d");
                                if ($destination=="showroom") {
                                    $q2="SELECT b_name, b_address, b_city, b_state
                                        FROM company
                                        WHERE branch_id=$destination_id";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)==1) {
                                            $row2=mysqli_fetch_array($res2);
                                            if (($destination_id>145000 && $destination_id<145999)
                                                || ($destination_id>148000 && $destination_id<148999)) {
                                                $destination_name=$row2["b_name"];
                                                $destination_city=$row2["b_city"];
                                                $destination_state=$row2["b_state"];
                                            } else {
                                                die("This branch is not a showroom.");
                                            }
                                        } else {
                                            die("Showroom not found.");
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                } else if ($destination=="dealer") {
                                    $q2="SELECT dealer_name, d_address, d_city, d_state
                                        FROM dealer
                                        WHERE dealer_id=$destination_id";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)==1) {
                                            $row2=mysqli_fetch_array($res2);
                                            $destination_name=$row2["dealer_name"];
                                            $destination_city=$row2["d_city"];
                                            $destination_state=$row2["d_state"];
                                        }
                                        else {
                                            die("Dealer not found.");
                                        } 
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                } 
                                $q3="SELECT product_name
                                FROM product
                                WHERE product_id=$product_id";
                                if ($res3=mysqli_query($link, $q3)) {
                                    if (mysqli_num_rows($res3) == 1) {
                                        $row3=mysqli_fetch_array($res3);
                                        $product_name=$row3["product_name"];
                                        $q4="SELECT product_stock, b_name, b_country, product_name
                                        FROM warehouse w
                                        INNER JOIN company c
                                        ON w.branch_id=c.branch_id
                                        INNER JOIN product p
                                        ON w.product_id=p.product_id
                                        WHERE w.branch_id=$_SESSION[branch_id]
                                        AND w.product_id=$product_id"; 
                                        if ($res4=mysqli_query($link, $q4)) {
                                            if (mysqli_num_rows($res4) == 1) {
                                                $row4=mysqli_fetch_array($res4);
                                                $actual_quantity=$row4["product_stock"];
                                                $branch_name=$row4["b_name"];
                                                $product_name=$row4["product_name"];
                                                $manufacture_date=date("Y-m-d");
                                                if ($actual_quantity-$quantity>0) { 
                                                    if ($destination=="showroom") {
                                                        $q5="INSERT INTO warehouse_transports
                                                            (product_id, manufacture_date, source_branch_id, destination_branch_id, destination_type, quantity, send_date) VALUES
                                                            ($product_id, '$manufacture_date', $_SESSION[branch_id], $destination_id, '$destination', $quantity, '$send_date')";
                                                    } else if ($destination=="dealer") {
                                                        $q5="INSERT INTO warehouse_transports
                                                            (product_id, manufacture_date, source_branch_id, destination_dealer_id, destination_type, quantity, send_date) VALUES
                                                            ($product_id, '$manufacture_date', $_SESSION[branch_id], $destination_id, '$destination', $quantity, '$send_date')";
                                                    }
                                                    if (mysqli_query($link, $q5)) {
                                                        $q6="UPDATE warehouse
                                                            SET product_stock=$actual_quantity-$quantity
                                                            WHERE branch_id=$_SESSION[branch_id]
                                                            AND product_id=$product_id";
                                                        if (mysqli_query($link, $q6)) {
                                                            echo "Transport of $quantity units of 
                                                                $product_name to $destination_name, 
                                                                $destination_city, $destination_state 
                                                                is successful.";
                                                        } else {
                                                            die("Error: ".mysqli_error($link));
                                                        }
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    echo "The required quantity of $product_name is NOT available.";
                                                }
                                            } else {
                                                echo "$product_name is not available at this branch.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                    } else {
                                        echo "Product ID not found.";
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