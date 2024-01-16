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
                        <br><h1>Add to Existing Bill</h1>
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
                                <input type="submit" value="Add">
                                <label></label>
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["quantity"])) {
                                $bill_id=$_POST["bill_id"];
                                $product_id=$_POST["product_id"];
                                $quantity=$_POST["quantity"];
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
                                                $q4="SELECT SUM(quantity)
                                                    FROM showroom
                                                    WHERE branch_id=$branch_id
                                                    AND product_id=$product_id";
                                                if ($res4=mysqli_query($link, $q4)) {
                                                    if (mysqli_num_rows($res4)==1) {   
                                                        $row4=mysqli_fetch_array($res4);
                                                        $total_quantity=$row4["quantity"];
                                                        $q5="SELECT SUM(quantity) as quantity
                                                            FROM bill_product
                                                            WHERE product_id=$product_id";
                                                        if ($res5=mysqli_query($link, $q5)) {
                                                            if (mysqli_num_rows($res5)==1) {
                                                                $row5=mysqli_fetch_array($res5);
                                                                $actual_quantity=$total_quantity-$row5["quantity"];
                                                            } else {
                                                                $actual_quantity=$total_quantity;
                                                            }
                                                            if ($actual_quantity-$quantity>0) {
                                                                $q6="SELECT quantity, manufacture_date
                                                                    FROM showroom
                                                                    WHERE branch_id=$branch_id
                                                                    AND product_id=$product_id
                                                                    ORDER BY manufacture_date";
                                                                if ($res6=mysqli_query($link, $q6)) {
                                                                    if (mysqli_num_rows($res6)>0) {
                                                                        $temp=$quantity;
                                                                        while ($row6=mysqli_fetch_array($res6)) {
                                                                            $total_product_quantity=$row6["quantity"];
                                                                            $manufacture_date=$row6["manufacture_date"];
                                                                            $q7="SELECT SUM(quantity) as quantity
                                                                                FROM bill_product bp
                                                                                INNER JOIN bill b
                                                                                ON bp.bill_id=b.bill_id
                                                                                WHERE product_id=$product_id
                                                                                AND manufacture_date=$manufacture_date
                                                                                AND branch_id=$branch_id
                                                                                AND status='UNPAID'";
                                                                            if ($res7=mysqli_query($link, $q7)) {
                                                                                if (mysqli_num_rows($res7)==1) {
                                                                                    $row7=mysqli_fetch_array($res7);
                                                                                    $available_quantity=$total_product_quantity-$row7["quantity"];
                                                                                } else {
                                                                                    $available_quantity=$total_product_quantity;
                                                                                }
                                                                                $q8="SELECT quantity
                                                                                    FROM bill_product
                                                                                    WHERE bill_id=$bill_id
                                                                                    AND product_id=$product_id
                                                                                    AND manufacture_date=$manufacture_date";
                                                                                if ($res8=mysqli_query($link, $q8)) {
                                                                                    if (mysqli_num_rows($res8)==1) {
                                                                                        $row8=mysqli_fetch_array($res8);
                                                                                        if ($temp<=$available_quantity) {
                                                                                            $q9="UPDATE bill_product
                                                                                                SET quantity=quantity+$temp
                                                                                                WHERE bill_id=$bill_id
                                                                                                AND product_id=$product_id
                                                                                                AND manufacture_dare=$manufacture_date";
                                                                                        } else {
                                                                                            $q9="UPDATE bill_product
                                                                                                SET quantity=quantity+$row8[quantity]
                                                                                                WHERE bill_id=$bill_id
                                                                                                AND product_id=$product_id
                                                                                                AND manufacture_dare=$manufacture_date";                                                                                            
                                                                                        }
                                                                                    } else {
                                                                                        if ($temp<=$available_quantity) {
                                                                                            $q9="INSERT INTO bill_product (bill_id, product_id, quantity, manufacture_date)
                                                                                            VALUES ($bill_id, $product_id, $temp, $manufacture_date)";
                                                                                        } else {
                                                                                            $q9="INSERT INTO bill_product (bill_id, product_id, quantity, manufacture_date)
                                                                                            VALUES ($bill_id, $product_id, $available_quantity, $manufacture_date)";                                                                                           
                                                                                        }
                                                                                    }
                                                                                    if (mysqli_query($link, $q9)) {
                                                                                        if ($temp<=$available_quantity)
                                                                                            break;
                                                                                        else 
                                                                                            $temp=$temp-$available_quantity;
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
                                                                        echo "Bill successfully updated.";
                                                                    }
                                                                } else {
                                                                    die("Error: ".mysqli_error($link));
                                                                }
                                                            } else {
                                                                echo "Required quantity of $product_name is not available.";
                                                            }
                                                        } else {
                                                            die("Error: ".mysqli_error($link));
                                                        }
                                                    } else {
                                                        echo "$product_name is not found.";
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