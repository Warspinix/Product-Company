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
                            <span style='font-size:16px'>$name, $country</span>";
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
                        <?php
                            $q2="SELECT warehouse_transport_id, pr.product_id, product_name, 
                                quantity, b_name, b_address, b_city, b_state
                                FROM warehouse_transports wt
                                INNER JOIN product pr
                                ON wt.product_id=pr.product_id
                                INNER JOIN company
                                ON source_branch_id=branch_id
                                WHERE destination_branch_id=$_SESSION[branch_id]";
                            $receive_date=date("Y-m-d");
                            if ($res2=mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2) > 0) {
                                    echo "
                                    <br><br><h1>Incoming Product Transports</h1>
                                    <table>
                                        <tr>
                                            <th>Transport ID</th>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Transported From</th>
                                        </tr>";
                                    while ($row2=mysqli_fetch_array($res2)) {
                                        echo "
                                        <tr>
                                            <td>$row2[warehouse_transport_id]</td>
                                            <td>$row2[product_id]</td>
                                            <td>$row2[product_name]</td>
                                            <td>$row2[quantity]</td>
                                            <td>$row2[b_name], $row2[b_address], $row2[b_city], $row2[b_state]</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<br><h1>No Incoming Product Transports.</h1>";
                                }
                            } else {
                                die("Error: ".mysqli_error($link));
                            }           
                        echo "</div>
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