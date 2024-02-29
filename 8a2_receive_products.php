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
                                        <li><a href='8a_products.php'>Products</a></li>
                                        <li><a href='8b_customers.php'>Customers</a></li>
                                        <li><a href='8c_bill_transaction.php'>Bills and Transactions</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='8a_products.php'>Products</a></li>
                                        <li><a href='8b_customers.php'>Customers</a></li>
                                        <li><a href='8c_bill_transaction.php'>Bills and Transactions</a></li>                                         
                                        <li><a href='8d_employee.php'>Employee</a></li>
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='8a_products.php'>Products</a></li>
                                        <li><a href='8b_customers.php'>Customers</a></li>
                                        <li><a href='8c_bill_transaction.php'>Bills and Transactions</a></li>                                         
                                        <li><a href='8d_employee.php'>Employee</a></li>
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
                        <div class="top">
                            <ul>
                                <li><a href="8a1_view_incoming_products.php">
                                   View Incoming Products
                                </a></li>
                                <li><a href="8a2_receive_products.php">
                                   Receive Products
                                </a></li>
                                <li><a href="8a3_check_product_stock.php">
                                    Check Product Stock
                                </a></li>
                            </ul>
                        </div>
                        <br><h1>Receive Products</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="wt_id" min=1 required>
                                <label for="wt_id">Transport ID</label>
                            </div>  
                            <div class="submit">
                                <input type="submit" value="Receive">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["wt_id"])) {
                                $warehouse_transport_id=$_POST["wt_id"];
                                $date=date("Y-m-d");
                                $q2="SELECT destination_branch_id 
                                    FROM warehouse_transports
                                    WHERE warehouse_transport_id=$warehouse_transport_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $destination_branch_id=$row2["destination_branch_id"];
                                        $q4="SELECT pr.product_id as product_id, product_name, 
                                            wt.quantity as quantity, manufacture_date, 
                                            b_name, b_address, b_city, b_state
                                            FROM warehouse_transports wt
                                            INNER JOIN product pr
                                            ON wt.product_id=pr.product_id
                                            INNER JOIN company
                                            ON source_branch_id=branch_id
                                            WHERE warehouse_transport_id=$warehouse_transport_id
                                            AND destination_branch_id=$_SESSION[branch_id]
                                            AND status='NOT RECEIVED'";
                                        if ($res4=mysqli_query($link, $q4)) {
                                            if (mysqli_num_rows($res4)==1) {
                                                $row4=mysqli_fetch_array($res4);
                                                $branch_id=$_SESSION["branch_id"];
                                                $product_id=$row4["product_id"];
                                                $product_name=$row4["product_name"];
                                                $quantity=$row4["quantity"];
                                                $manufacture_date=$row4["manufacture_date"];
                                                echo "
                                                <table>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <td>$row4[product_name]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>From</th>
                                                        <td>$row4[b_name], $row4[b_address], $row4[b_city], 
                                                        $row4[b_state]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Quantity</th>
                                                        <td>$quantity</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Manufacture Date</th>
                                                        <td>$manufacture_date</td>                                    
                                                    </tr>
                                                </table>";
                                                $q5="SELECT product_stock
                                                    FROM showroom
                                                    WHERE branch_id=$branch_id
                                                    AND product_id=$product_id
                                                    AND manufacture_date=$manufacture_date";
                                                if ($res5=mysqli_query($link, $q5)) {
                                                    if (mysqli_num_rows($res5)==1) {
                                                        $row5=mysqli_fetch_array($res5);
                                                        $actual_quantity=$row5["product_stock"];
                                                        $q6="UPDATE showroom
                                                            SET product_stock=$actual_quantity+$quantity
                                                            WHERE branch_id=$branch_id
                                                            AND product_id=$product_id
                                                            AND manufacture_date='$manufacture_date'";
                                                    } else {
                                                        $q6="INSERT INTO showroom values
                                                            ($branch_id, $product_id, $quantity, '$manufacture_date')";
                                                    }
                                                    if (mysqli_query($link, $q6)) {
                                                        $q7="UPDATE warehouse_transports
                                                            SET receive_date='$date',
                                                            status='RECEIVED'
                                                            WHERE warehouse_transport_id=$warehouse_transport_id";
                                                        if (mysqli_query($link, $q7)) {
                                                            echo "<br>Product Received.";
                                                        }
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                 } else {
                                                    die("Error: ".mysqli_error($link));
                                                 }
                                            } else {
                                                echo "The given transport ID is not associated with this branch.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                    } else {
                                        echo "Transport ID not found.";
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