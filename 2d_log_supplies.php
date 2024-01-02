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
                        <div class='left'><br><br>".
                            $name. ", ".$country;
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul>
                                        <li><a href='2a_check_spares.php'>Check Spares</a></li>
                                        <li><a href='2b_view_orders.php'>View Orders</a></li>
                                        <li><a href='2c_view_production_details.php'>View Production Details</a></li>
                                        <li><a href='2d_log_supplies.php'>Log Supplies</a></li>
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                        <li><a href='2a_check_spares.php'>Check Spares</a></li>
                                        <li><a href='2b_view_orders.php'>View Orders</a></li>
                                        <li><a href='2c_view_production_details.php'>View Production Details</a></li>
                                        <li><a href='2d_log_supplies.php'>Log Supplies</a></li>
                                        <li><a href='2e_make_orders.php'>Make Orders</a></li>
                                    </ul>";
                            } else {
                                echo "<ul>
                                        <li><a href='2a_check_spares.php'>Check Spares</a></li>
                                        <li><a href='2b_view_orders.php'>View Orders</a></li>
                                        <li><a href='2c_view_production_details.php'>View Production Details</a></li>
                                        <li><a href='2d_log_supplies.php'>Log Supplies</a></li>
                                        <li><a href='2e_make_orders.php'>Make Orders</a></li>
                                    </ul>";
                            }
                            echo "
                            <div class='profile-section'>
                                <br>
                                <div class='username'>
                                    ".$_SESSION['fname']." ".$_SESSION['lname']."
                                </div>
                                    <a href='0_view_profile.php'><button class='edit-button'>View Profile</button></a>
                                    <a href='0_logout.php'><button class='logout'>Logout</button></a><br>
                            </div>
                        </div>
                        <div class='main'>";
                        ?>
                        <br><br><h1>Log Supplies</h1>
                        <form method="POST">
                            <br><br>
                            <label for="order_id">Order ID:</label>
                            <input type="number" name="order_id" required>
                            <br><br>
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" required>
                            <br><br>
                            <input type="submit" value="Log Supply" required>
                            <br><br>
                        </form>
                        <?php
                        if (isset($_POST["quantity"])) {
                            $order_id=$_POST["order_id"];
                            $quantity=$_POST["quantity"];   
                            $date=date("Y-m-d");
                            $q2="SELECT * FROM orders
                                WHERE order_id=$order_id";
                            if ($res2 = mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2) == 1) {
                                    $q3 = "INSERT INTO supplies (order_id, supply_date, quantity) VALUES
                                    ($order_id, '$date', $quantity)";                                
                                    if (mysqli_query($link, $q3)) {
                                        echo "Supply Successfully Logged.";
                                    } else {
                                        die("<br><br>Error: ".mysqli_error($link));
                                    }
                                } else {
                                    echo "<br>Order ID doesn't exist.";
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