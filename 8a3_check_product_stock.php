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
                        <br><h1>Check Product Stock</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="product_id" required>
                                <label for="product_id">Product ID</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Check">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["product_id"])) {
                                $product_id=$_POST["product_id"];
                                $q2="SELECT product_name
                                    FROM product
                                    WHERE product_id=$product_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $product_name=$row2["product_name"];
                                        $q3="SELECT quantity, manufacture_date
                                            FROM showroom
                                            WHERE branch_id=$_SESSION[branch_id]
                                            AND product_id=$product_id";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)>0) {
                                                echo "Stock of $product_name<br>
                                                <table>
                                                    <tr>
                                                        <th>Manufacture Date</th>
                                                        <th>Quantity</th>
                                                    </tr>";
                                                while ($row3=mysqli_fetch_array($res3)) {
                                                    echo "
                                                    <tr>
                                                        <td>$row3[manufacture_date]</td>
                                                        <td>$row3[quantity]</td>
                                                    </tr>";
                                                }
                                                echo "
                                                </table>";
                                            } else {
                                                echo "$product_name not available in this branch.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                    } else {
                                        echo "Invalid Product ID.";
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