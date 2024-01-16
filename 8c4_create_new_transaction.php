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
                                <li><a href="8c1_create_new_bill.php">
                                    Create New Bill
                                </a></li>
                                <li><a href="8c2_add_to_existing_bill.php">
                                    Add to Existing Bill
                                </a></li>
                                <li><a href="8c3_remove_from_existing_bill.php">
                                    Remove From Existing Bill
                                </a></li>
                                <li><a href="8c4_create_new_transaction.php">
                                    Create New Transaction
                                </a></li>
                                <li> <a href="8c5_update_existing_transaction.php">
                                    Update Existing Transaction
                                </a></li>
                                <li> <a href="8c6_view_bill.php">
                                    View Bill
                                </a></li>
                                <li> <a href="8c7_view_transaction.php">
                                    View Transaction
                                </a></li>     
                            </ul>
                        </div>
                        <br><h1>Create New Transaction</h1> 
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="bill_id" required>
                                <label for="bill_id">Bill ID</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Create">
                            </div>
                        <?php
                            if (isset($_POST["bill_id"])) {
                                $bill_id=$_POST["bill_id"];
                                $q2="SELECT * FROM bill
                                    WHERE bill_id=$bill_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    $q2="SELECT currency
                                        FROM country_currency
                                        WHERE country=$country";
                                    if ($res2=mysqli_query($link, $q2)) { 
                                        if(mysqli_num_rows($res2)==1) { 
                                            $row2=mysqli_fetch_array($res2);
                                            $currency=$row2["currency"];
                                            $q3="SELECT SUM(bp.quantity)*price as cost
                                                FROM product p
                                                INNER JOIN product_country_prices pcp
                                                ON p.product_id=pcp.product_id
                                                INNER JOIN bill_product bp
                                                ON p.product_id=bp.product_id
                                                WHERE bill_id=$bill_id";
                                            if ($res3=mysqli_query($link, $q3)) {
                                                if (mysqli_num_rows($res3)>0) {
                                                    $total_amount=0;
                                                    while ($row3=mysqli_fetch_array($res3)) {
                                                        $total_amount+=$row3["amount"];
                                                    }
                                                    $q4="INSERT INTO transaction (bill_id, total_amount) 
                                                        VALUES ($bill_id, $total_amount)";
                                                    if ($res4=mysqli_query($link, $q4)) {
                                                        $q5="SELECT MAX(transancation_id) as transaction_id
                                                            FROM transaction
                                                            WHERE bill_id=$bill_id
                                                            AND total_amount=$total_amount";
                                                            if ($res5=mysqli_query($link, $q5)) {
                                                                $row5=mysqli_fetch_array($res5);
                                                                $transaction_id=$row5["transaction_id"];
                                                                echo "Transaction Created.<br>
                                                                    Transaction ID: $transaction_id
                                                                    Total Amount=$total_amount";
                                                            } else {
                                                                die("Error: ".mysqli_error($link));
                                                            }
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    echo "No records found.";
                                                }
                                            } else {
                                                die("Error: ".mysqli_error($link));
                                            }
                                        } else {
                                            echo "Country not found.";
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
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