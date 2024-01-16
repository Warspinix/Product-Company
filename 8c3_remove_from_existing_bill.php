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
                        <br><h1>Remove From Existing Bill</h1> 
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="bill_id" required>
                                <label for="bill_id">Bill ID</label>
                            </div>
                            <div class="field">
                                <input type="number" name="product_id" required>
                                <label for="product_id">Product ID</label>
                            </div>
                            <div class="field">
                                <input type="number" name="quantity" required>
                                <label for="quantity">Quantity</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Remove">
                                <label></label>
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["quantity"])) {
                                $bill_id=$_POST["bill_id"];
                                $product_id=$_POST["product_id"];
                                $quantity=$_POST["quantity"];
                                $quantity_to_be_removed=$quantity;
                                $branch_id=$_SESSION["branch_id"];
                                $q2="SELECT *
                                    FROM bill
                                    WHERE bill_id=$bill_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $q3="SELECT product_name
                                            FROM product
                                            WHERE product_id=$product_id";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)== 1) {
                                                $row3=mysqli_fetch_array($res3);
                                                $product_name=$row3["product_name"];
                                                $q4="SELECT SUM(quantity) as quantity
                                                    FROM bill_product
                                                    WHERE bill_id=$bill_id
                                                    AND product_id=$product_id
                                                    AND status='UNPAID'";                                                    
                                                if ($res4=mysqli_query($link, $q4)) {
                                                    if (mysqli_num_rows($res4)==1) {   
                                                        $row4=mysqli_fetch_array($res4);
                                                        $quantity_in_bill=$row4["quantity"];
                                                        if ($quantity_to_be_removed<=$quantity_in_bill) {
                                                            $q5="SELECT quantity, manufacture_date
                                                                FROM bill_product
                                                                WHERE bill_id=$bill_id
                                                                AND product_id=$product_id";
                                                            if ($res5=mysqli_query($link, $q5)) {
                                                                if (mysqli_num_rows($res5)>0) { 
                                                                    while ($row5=mysqli_fetch_array($res5)) {
                                                                        $manufacture_date=$row5["manufacuture_date"];
                                                                        $quantity_in_bill_with_md=$row5["quantity"];
                                                                        if ($quantity_to_be_removed<=$quantity_in_bill_with_md) {
                                                                            $q6="UPDATE bill_product
                                                                                SET quantity=quantity-$quantity_to_be_removed
                                                                                WHERE bill_id=$bill_id
                                                                                AND product_id=$product_id
                                                                                AND manufacture_date=$manufacture_date";
                                                                        } else {
                                                                            $q6="UPDATE bill_product
                                                                                SET quantity=quantity-$quantity_in_bill_with_md
                                                                                WHERE bill_id=$bill_id
                                                                                AND product_id=$product_id
                                                                                AND manufacture_date=$manufacture_date";
                                                                        }
                                                                        if (mysqli_query($link, $q6)) {
                                                                            if ($quantity_to_be_removed<=$quantity_in_bill_with_md)
                                                                                break;
                                                                            else
                                                                                $quantity_to_be_removed=$quantity_to_be_removed-$quantity_in_bill_with_md;
                                                                        } else {
                                                                            die("Error: ".mysqli_error($link));
                                                                        }
                                                                    }
                                                                    echo "$quantity units of $product_name successfully deleted form $bill_id";
                                                                    $q7="DELETE FROM bill_product
                                                                        WHERE bill_id=$bill_id
                                                                        AND product_id=$product_id
                                                                        AND quantity=0";
                                                                    if (mysqli_query($link, $q7)) {
                                                                        $q8="SELECT * FROM bill_product
                                                                            WHERE bill_id=$bill_id";
                                                                        if (mysqli_query($link, $q8)) {
                                                                            if (mysqli_num_rows($res8)<0) {
                                                                                $q9="DELETE FROM bill
                                                                                    WHERE bill_id=$bill_id";
                                                                            }
                                                                            echo "<br>No more items present in the bill, so bill has been deleted.";
                                                                        }
                                                                    }
                                                                }                                   
                                                            } else {
                                                                die("Error: ".mysqli_error($link));
                                                            }
                                                        } else {
                                                            echo "Bill only has $quantity_in_bill units of $product_name.";
                                                        }
                                                    } else {
                                                        echo "$product_name is not found in this bill.";
                                                    }
                                                } else {
                                                    die("Error: ".mysqli_error($link));
                                                }
                                            } else {
                                                echo "Product ID not found.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                    } else {
                                        echo "Bill not found.";
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