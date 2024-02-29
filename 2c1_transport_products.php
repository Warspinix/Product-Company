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
                                        <li><a href='2a_spares.php'>Spares</a></li>                                        
                                        <li><a href='2b_manufacturing.php'>Manufacturing</a></li>
                                        <li><a href='2c_transports.php'>'Transports</a></li>                                    
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='2a_spares.php'>Spares</a></li>                                        
                                        <li><a href='2b_manufacturing.php'>Manufacturing</a></li>
                                        <li><a href='2c_transports.php'>Transports</a></li>
                                        <li><a href='2d_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='2a_spares.php'>Spares</a></li>                                        
                                        <li><a href='2b_manufacturing.php'>Manufacturing</a></li>
                                        <li><a href='2c_transports.php'>Transports</a></li>
                                        <li><a href='2d_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            }
                            echo "
                            <div class='profile-section'>
                                <div class='username'>
                                    <br>
                                    ".$_SESSION['fname']." ".$_SESSION['lname']."
                                </div>
                                    <a href='0_view_profile.php'><button class='edit-button'>View Profile</button></a>
                                    <a href='0_logout.php'><button class='logout'>Logout</button></a><br>
                            </div>
                        </div>
                        <div class='main'>
                            <div class='top'>";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul>
                                    <li><a href='2c1_transport_products.php'>
                                        Transport Products
                                    </a></li>
                                </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                    <li><a href='2c1_transport_products.php'>
                                        Transport Products
                                    </a></li>
                                    <li><a href='2c2_view_transports.php'>
                                        View Transports
                                    </a></li>
                                </ul>";
                            } else {
                                echo "<ul>
                                    <li><a href='2c1_transport_products.php'>
                                        Transport Products
                                    </a></li>
                                    <li><a href='2c2_view_transports.php'>
                                        View Transports
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
                                <input type="number" name="manufacture_id" required>
                                <label for="manufacture_id">Manufacture ID</label>
                            </div>                            
                            <div class="field">
                                <input type="number" name="quantity" required>
                                <label for="quantity">Quantity</label>
                            </div>
                            <div class="field">                            
                                <input type="number" name="warehouse_id" required>
                                <label for="warehouse_id">Warehouse ID</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Update">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["warehouse_id"])) {
                                $manufacture_id=$_POST["manufacture_id"];
                                $quantity=$_POST["quantity"];
                                $warehouse_id=$_POST["warehouse_id"];
                                $send_date=date("Y-m-d");
                                $q2="SELECT quantity FROM manufactures 
                                    WHERE manufacture_id=$manufacture_id
                                    AND source_branch_id=$_SESSION[branch_id]";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $existing_quantity=$row2["quantity"];
                                        if ($existing_quantity>=$quantity) {
                                            $remaining_quantity=$existing_quantity-$quantity;
                                            $q3="SELECT * FROM company
                                                WHERE branch_id=$warehouse_id";
                                            if ($res3=mysqli_query($link, $q3)) {
                                                if (mysqli_num_rows($res3)==1) {
                                                    $row3=mysqli_fetch_array($res3);
                                                    if ($row3["branch_id"]>=144000 && $row3["branch_id"]<=144999) {
                                                        $q4="INSERT INTO transports
                                                            (manufacture_id, quantity, warehouse_id, send_date)
                                                            VALUES ($manufacture_id, $quantity, $warehouse_id, '$send_date')";
                                                        if (mysqli_query($link, $q4)) {
                                                            $q5="UPDATE manufactures
                                                                SET quantity=quantity-$quantity
                                                                WHERE manufacture_id=$manufacture_id";
                                                                if (mysqli_query($link, $q5)) {
                                                                    echo "<br>Transport Logged.";
                                                                    if ($remaining_quantity==0) {
                                                                        $q6="UPDATE manufactures
                                                                            SET status='SENT'
                                                                            WHERE manufacture_id=$manufacture_id";
                                                                        if (mysqli_query($link, $q6)) {
                                                                            echo "<br>All units of this manufacture ID have been sent.";
                                                                        } else {
                                                                            die("<br>Error: ".mysqli_error($link));
                                                                        }
                                                                    }
                                                                } else {

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
                                            echo "Required quantity not available.";
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