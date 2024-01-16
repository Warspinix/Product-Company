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
                        <br><h1>Update Existing Transaction</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="transaction_id" required>
                                <label for="transaction_id">Transaction ID</label>
                            </div>
                            <div class="field">
                                <select name="payment_method" required>
                                    <option value="" disable select>Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="upi">UPI</option>
                                    <option value="card">Credit or Debit Card</option>
                                </select>
                            </div>
                            <div class="field">
                                <select name="points" required>
                                    <option value="" disable select>Use Points?</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Confirm">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["points"])) {
                                $transaction_id=$_POST["transaction_id"];
                                $payment_method=$_POST["payment_method"];
                                $points=$_POST["points"];
                                $transaction_date=date("Y-m-d");
                                $branch_id=$_SESSION["branch_id"];
                                $q2="SELECT bill_id, total_amount
                                    FROM transaction
                                    WHERE transaction_id=$transaction_id";
                                if ($res2 = mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $bill_id=$row2["bill_id"];
                                        $total_amount=$row2["total_amount"];
                                        if ($points="yes") {
                                            $total_amount=$total_amount-$points;
                                        }
                                        $q3="SELECT product_id, quantity, manufacture_date
                                            FROM bill_product
                                            WHERE bill_id=$bill_id
                                            ORDER BY manufacture_date";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)== 1) {
                                                while ($row3=mysqli_fetch_array($res3)) {
                                                    $product_id=$row3["product_id"];
                                                    $quantity=$row3["quantity"];
                                                    $manufacture_date=$row3["manufacture_date"];
                                                    $q4="UPDATE showroom
                                                        SET quantity=quantity-$quantity
                                                        WHERE branch_id=$branch_id
                                                        AND product_id=$product_id
                                                        AND manufacture_date=$manufacture_date";
                                                    if (mysqli_query($link, $q4)) {
                                                        $q5="UPDATE bill_product
                                                            SET status='PAID'
                                                            WHERE bill_id=$bill_id
                                                            AND product_id=$product_id
                                                            AND manufacture_date=$manufacture_date";
                                                        if (mysqli_query($link, $q5)) {
                                                        } else {
                                                            die("Error: ".mysqli_error($link));
                                                        }
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                }
                                                $q6="UPDATE transaction SET
                                                    total_amount=$total_amount,
                                                    transaction_date=$transaction_date,
                                                    payment_method=$payment_method,
                                                    status='SUCCESSFUL'
                                                    WHERE transaction_id=$transaction_id";
                                                if (mysqli_query($link, $q6)) {
                                                    $q7="SELECT phone_no
                                                        FROM bill
                                                        WHERE bill_id=$bill_id";
                                                    if ($res7=mysqli_query($link, $q7)) {
                                                        $row7=mysqli_fetch_array($res7);
                                                        $phone_no=$row7["customer_id"];
                                                        $q8="UPDATE customer
                                                            SET points=0+($total_amount/1000)
                                                            WHERE phone_no=$phone_no";
                                                        if (mysqli_query($link, $q8)) {
                                                            echo "Transaction successful.";
                                                        } else {
                                                            die("Error: ".mysqli_error($link));
                                                        }                                                          
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    die("Error: ".mysqli_error($link));
                                                }
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }                                                                                  
                                    } else {
                                        echo "Transaction ID not found.";
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