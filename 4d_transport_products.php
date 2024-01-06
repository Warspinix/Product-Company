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
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_ongoing_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4e_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_ongoing_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4f_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                        <li><a href='4d_transport_products.php'>Transport Products</a></li>
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_ongoing_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4f_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                        <li><a href='4d_transport_products.php'>Transport Products</a></li>
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
                        <div class='main' id='mainID'>";
                        ?>
                        <br><br><h1>Transport Products</h1>
                        <form method="POST">
                            <br><br>
                            <select name="destination" required>
                                <option value="" disable select>Showroom or Dealer</option>
                                <option value="showroom">Showroom</option>
                                <option value="dealer">Dealer</option>
                            </select>
                            <br><br>
                            <input type="number" name="destination_id" placeholder="Destination ID" required>
                            <br><br>
                            <input type="number" name="product_id" placeholder="Product ID" required>
                            <br><br>
                            <input type="number" name="quantity" min=1 placeholder="Quantity" required>
                            <br><br>
                            <input type="submit" value="Schedule Transport">
                        </form>
                        <?php
                            if (isset($_POST["quantity"])) {
                                $destination=$_POST["destination"];
                                $destination_id=$_POST["destination_id"];
                                $product_id=$_POST["product_id"];
                                $quantity=$_POST["quantity"];
                                $send_date=date("Y-m-d");
                                $q2="SELECT product_name
                                FROM product
                                WHERE product_id=$product_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2) == 1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $product_name=$row2["product_name"];
                                        $q3="SELECT product_stock, branch_name, branch_country product_name
                                        FROM warehouse w
                                        INNER JOIN company c
                                        ON w.branch_id=c.branch_id
                                        INNER JOIN product p
                                        ON w.product_id=p.product_id
                                        WHERE w.branch_id=$_SESSION[branch_id]
                                        AND w.product_id=$product_id"; 
                                        if ($res3=mysqli_query($link, $q3)) {
                                                if (mysqli_num_rows($res3) == 1) {
                                                $actual_quantity=$row2["product_stock"];
                                                $branch_name=$row2["branch_name"];
                                                $product_name=$row2["product_name"];
                                                if ($actual_quantity-$quantity>0) { 
                                                    if ($destination="showroom") {
                                                        $q5="INSERT INTO warehouse_to_showroom
                                                            (product_id, source_branch_id, destination_branch_id, quantity, send_date) VALUES
                                                            ($product_id, $SESSION[branch_id], $destination_id, $quantity, $send_date)";
                                                    } else if ($destination="dealer") {
                                                        $q5="INSERT INTO warehouse_to_dealer
                                                            (product_id, source_branch_id, destination_branch_id, quantity, send_date) VALUES
                                                            ($product_id, $SESSION[branch_id], $destination_id, $quantity, $send_date)";
                                                    }
                                                    if (mysqli_query($link, $q5)) {
                                                        $q6="UPDATE warehouse
                                                                SET product_stock=$actual_quantity-$quantity
                                                                WHERE branch_id=$_SESSION[branch_id]
                                                                AND product_id=$product_id";
                                                        if (mysqli_query($link, $q6)) {
                                                            echo "<br><br>Transport of $quantity units of 
                                                                $product_name to $branch_name, $branch_country successful";
                                                        } else {
                                                            die("<br><br>Error: ".mysqli_error($link));
                                                        }
                                                    } else {
                                                        die("<br><br>Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    echo "<br><br>The required quantity of $product_name is NOT available.";
                                                }
                                            } else {
                                                echo "<br><br>This product is not available at this branch.";
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
                        echo "
                        </div>
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