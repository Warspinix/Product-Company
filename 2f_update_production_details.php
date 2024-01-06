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
                        <div class='left'><br><br>
                            $name, $country";
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
                        <br><br><h1>Update Production Details</h1>
                        <form method="POST">
                            <br><br>
                            <label for="product_id">Product ID:</label>
                            <input type="number" name="product_id" required>
                            <br><br>
                            <label for="destination_branch_id">Destination Branch ID:</label>
                            <input type="number" name="destination_branch_id" required>
                            <br><br>
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" required>
                            <br><br>
                            <input type="submit" value="Update">
                        </form>
                        <?php
                            if (isset($_POST["quantity"])) {
                                $product_id=$_POST["product_id"];
                                $destination_branch_id=$_POST["destination_branch_id"];
                                $quantity=$_POST["quantity"];
                                $manufacture_date=date("Y-m-d");
                                $q2="SELECT * FROM product 
                                    WHERE product_id=$product_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2) == 1) {
                                        $q3="SELECT *
                                            FROM project p
                                            INNER JOIN project_branch pb
                                            ON p.project_id=pb.project_id
                                            INNER JOIN company c
                                            ON pb.branch_id=c.branch_id
                                            INNER JOIN project_product ppr
                                            ON p.project_id=ppr.project_id
                                            INNER JOIN product pr
                                            ON ppr.product_id=pr.product_id
                                            WHERE p.product_id=$product_id
                                            AND pb.branch_id=$_SESSION[branch_id]";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3) == 1) {
                                                $q4="SELECT * FROM company 
                                                    WHERE branch_id=$destination_branch_id"; 
                                                if ($res4=mysqli_query($link, $q4)) {
                                                    if (mysqli_num_rows($res4) == 1) { 
                                                        $row4=mysqli_fetch_array($res4);
                                                        if (floor($row4["branch_id"]/1000)=="144") {
                                                            $q5="INSERT INTO transports (product_id, source_branch_id, destination_branch_id, quantity, manufacture_date) VALUES
                                                                ($product_id, $_SESSION[branch_id], $destination_branch_id, $quantity, $manufacture_date)";
                                                            if (mysqli_query($link, $q5)) {
                                                                echo "<br><br>Update Successful.";
                                                            } else {
                                                                die("<br><br>Error: ".mysqli_error($link));
                                                            }
                                                        } else {
                                                            echo "<br><br>This branch is NOT a warehouse.";
                                                        }
                                                    } else {
                                                        echo "<br><br>Branch ID not found.";
                                                    }
                                                } else {
                                                    die("<br><br>Error: ".mysqli_error($link));
                                                }
                                            } else {
                                                echo "<br><br>This product is not being made in our branch.";
                                            }
                                        } else {
                                            die("<br><br>Error: ".mysqli_error($link));
                                        }                                            
                                    } else {
                                        echo "<br><br>Product ID not found.";
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