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
                                        <li><a href='2i_view_nearby_warehouses.php'>View Nearby Warehouses</a></li>
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
                        <br><br><h1>Transport Products</h1>
                        <form method="POST">
                            <br><br>
                            <div class="field">
                                <input type="number" name="manufacture_id" required>
                                <label for="manufacture_id">Manufacture ID</label>
                            </div>
                            <div class="field">                            
                                <input type="number" name="destination_branch_id" required>
                                <label for="destination_branch_id">Destination Branch ID</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Update">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["destination_branch_id"])) {
                                $manufacture_id=$_POST["manufacture_id"];
                                $destination_branch_id=$_POST["destination_branch_id"];
                                $send_date=date("Y-m-d");
                                $q2="SELECT * FROM manufactures 
                                    WHERE manufacture_id=$manufacture_id
                                    AND source_branch_id=$_SESSION[branch_id]";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $q3="SELECT * FROM company
                                            WHERE branch_id=$destination_branch_id";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)==1) {
                                                $row3=mysqli_fetch_array($res3);
                                                if ($row3["branch_id"]>=144000 && $row3["branch_id"]<=144999) {
                                                    $q4="INSERT INTO transports
                                                        (manufacture_id, destination_branch_id, send_date)
                                                        VALUES ($manufacture_id, $destination_branch_id, $send_date)";
                                                    if (mysqli_query($link, $q4)) {
                                                        $q5="UPDATE manufactures
                                                            SET status='SENT'
                                                            WHERE manufacture_id=$manufacture_id";
                                                        if (mysqli_query($link, $q5)) {
                                                            echo "<br>Transport Logged.";
                                                        } else {
                                                            die("<br>Error: ".mysqli_error($link));
                                                        }
                                                    } else {
                                                        die("<br>Error: ".mysqli_error($link));
                                                    }    
                                                } else {
                                                    echo "<br>This branch is not a warehouse.";
                                                }
                                            } else {
                                                echo "<br>Branch doesn't exist.";
                                            }
                                        } else {
                                            die("<br>Error: ".mysqli_error($link));
                                        }                
                                    } else {
                                        echo "Manufacture ID not found.";
                                    }
                                } else {
                                    die("<br>Error: ".mysqli_error($link));
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