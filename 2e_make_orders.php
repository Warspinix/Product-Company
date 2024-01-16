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
                            <span style='font-size:15px; margin-top: 13px;'>$name, $country</span>";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul><br>
                                        <li><a href='2a_check_spares.php'>Check Spares</a></li>
                                        <li><a href='2b_view_orders.php'>View Orders</a></li>
                                        <li><a href='2c_view_production_details.php'>View Production Details</a></li>
                                        <li><a href='2d_log_supplies.php'>Log Supplies</a></li>
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='2a_check_spares.php'>Check Spares</a></li>
                                        <li><a href='2b_view_orders.php'>View Orders</a></li>
                                        <li><a href='2c_view_production_details.php'>View Production Details</a></li>
                                        <li><a href='2d_log_supplies.php'>Log Supplies</a></li>
                                        <li><a href='2e_make_orders.php'>Make Orders</a></li>
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='2a_check_spares.php'>Check Spares</a></li>
                                        <li><a href='2b_view_orders.php'>View Orders</a></li>
                                        <li><a href='2c_view_production_details.php'>View Production Details</a></li>
                                        <li><a href='2d_log_supplies.php'>Log Supplies</a></li>
                                        <li><a href='2e_make_orders.php'>Make Orders</a></li>
                                        <li><a href='2f_update_production_details.php'>Update Production Details</a></li>
                                        <li><a href='2g_view_manufactures.php'>View Manufactures</a></li>
                                        <li><a href='2h_transport_products.php'>Transport Products</a></li>
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
                        <div class='main'>";
                        ?>
                        <br><br><h1>Make Orders</h1>
                        <form method="POST">
                            <br><br>
                            <div class="field">
                                <input type="number" name="spare_id" required>
                                <label for="spare_id">Spare ID</label>
                            </div>
                            <div class="field">
                            <input type="number" name="quantity" required>
                                <label for="quantity">Quantity</label>
                            </div>
                            <div class="field">
                                <input type="number" name="supplier_id" required>
                                <label for="supplier_id">Supplier ID</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Submit Order">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["supplier_id"])) {
                                $spare_id=$_POST["spare_id"];
                                $quantity=$_POST["quantity"];
                                $supplier_id=$_POST["supplier_id"];
                                $order_date=date("Y-m-d");
                                $q2="SELECT * FROM spares WHERE spare_id=$spare_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2) == 1) {
                                        $q3="SELECT * FROM suppliers WHERE supplier_id=$supplier_id";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3) == 1) {
                                                $q4="SELECT part_name FROM spares WHERE spare_id=$spare_id";
                                                $q5="SELECT supplier_name FROM suppliers WHERE supplier_id=$supplier_id";
                                                if ($res4=mysqli_query( $link, $q4)) {
                                                    $row4=mysqli_fetch_array($res4);
                                                    $part_name=$row4["part_name"];
                                                } else {
                                                    die("<br><br>Error: ".mysqli_error($link));
                                                }
                                                if ($res5=mysqli_query($link, $q5)) {
                                                    $row5=mysqli_fetch_array($res5);
                                                    $supplier_name=$row5["supplier_name"];
                                                } else {
                                                    die("<br><br>Error: ".mysqli_error($link));
                                                }
                                                $q6="SELECT * FROM spares_suppliers
                                                    WHERE spare_id=$spare_id 
                                                    AND supplier_id=$supplier_id";
                                                if ($res6=mysqli_query($link, $q6)) {
                                                    if (mysqli_num_rows($res6) == 1) {
                                                    $q7="INSERT INTO orders (order_date, spare_id, quantity, supplier_id, branch_id) VALUES
                                                        ($order_date, $spare_id, $quantity, $supplier_id,)";
                                                    } else {
                                                        echo "<br><br>$supplier_name doesn't supply $part_name.";
                                                    }
                                                } else {
                                                    die("<br><br>Error: ".mysqli_error($link));
                                                }
                                            } else {
                                                echo "<br><br>Supplier ID doesn't exist.";
                                            }
                                        } else {
                                            die("<br><br>Error: ".mysqli_error($link));
                                        }
                                    } else {
                                        echo "<br><br>Spare ID doesn't exist.";
                                    }
                                } else {
                                    die("<br><br>Error: ".mysqli_error($link));
                                }
                            }           
                        echo "</div>
                    </div>
                ";
            } else {
                die("<br><br>Error: ".mysqli_error($link));
            }
        } else {
            echo "<br><br><div style='text-align:center;'><h1>You aren't logged in.</h1><br>
                    <a href='0_home.html'><button class='edit-button'>Go Home</button></a>&emsp;
                    <a href='0_login.php'><button class='edit-button'>Login</button></a></div><br><br>";
        }
    ?>
    </body>
</html>