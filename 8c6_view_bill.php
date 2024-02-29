<?php
    session_start();
?>
<html>
    <head>
        <title>View Bill</title>
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
                        <br><h1>View Bill(s)</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <select name="table" required>
                                    <option value="" disable select>Search By</option>
                                    <option value="bill">Bill ID</option>
                                    <option value="customer">Phone Number</option>
                                    <option value="product">Product ID</option>
                                </select>
                            </div>
                            <div class="field">
                                <select name="status" required>
                                    <option value="" disable select>Paid or Unpaid?</option>
                                    <option value="PAID">Paid</option>
                                    <option value="UNPAID">Unpaid</option>
                                </select>
                            </div>
                            <div class="field">
                                <input type="number" name="id" required>
                                <label for="id">Search</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Search">
                            </div>
                        </form>
                        <?php
                        if (isset($_POST["id"])) {
                            $table=$_POST["table"];
                            $status=$_POST["status"];
                            $id=$_POST["id"];
                            $branch_id=$_SESSION["branch_id"];
                            $q2="SELECT b_name, b_address, b_city, 
                                b_code, b_state, b_country
                                FROM company
                                WHERE branch_id=$branch_id";
                            if ($res2=mysqli_query($link, $q2)) {
                                $row2=mysqli_fetch_array($res2);
                                $branch="$row2[b_name], $row2[b_address], 
                                $row2[b_city] - $row2[b_code], $row2[b_state], $row2[b_country]";
                                if ($table=="bill") {
                                    $q3="SELECT bill_id, customer_id, employee_id, date_issued
                                        FROM bill
                                        WHERE bill_id=$id
                                        AND branch_id=$branch_id";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3)==1) {
                                            $row3=mysqli_fetch_array($res3);
                                            $bill_id=$row3["bill_id"];
                                            $phone_no=$row3["customer_id"];
                                            $employee_id=$row3["employee_id"];
                                            $date_issued=$row3["date_issued"];
                                            $q4="SELECT fname, lname
                                                FROM customer
                                                WHERE phone_no=$phone_no";
                                            if ($res4=mysqli_query($link, $q4)) {
                                                $row4=mysqli_fetch_array($res4);
                                                $customer_name="$row4[fname] $row4[lname]";
                                                $q5="SELECT fname, lname, position
                                                    FROM employee
                                                    WHERE employee_id=$employee_id";
                                                if ($res5=mysqli_query($link, $q5)) {
                                                    $row5=mysqli_fetch_array($res5);
                                                    $employee_name="$row5[fname] $row5[lname]";
                                                    $position=$row5["position"];
                                                    $q6="SELECT p.product_id, product_name, 
                                                        SUM(quantity) as quantity,
                                                        price as unit_cost, 
                                                        SUM(quantity)*price as total_cost
                                                        FROM bill_product bp
                                                        INNER JOIN product p
                                                        ON bp.product_id=p.product_id                                                       
                                                        WHERE bill_id=$id
                                                        AND status='$status'
                                                        GROUP BY p.product_id";
                                                    if ($res6=mysqli_query($link, $q6)) {
                                                        if (mysqli_num_rows($res6)>0) {
                                                            echo "
                                                            Bill ID: $bill_id
                                                            &emsp;&emsp;
                                                            Date Issued: $date_issued
                                                            &emsp;&emsp;
                                                            Customer Name: $customer_name
                                                            <br>
                                                            Employee ID: $employee_id
                                                            &emsp;&emsp;
                                                            Employee Name: $employee_name
                                                            <br>
                                                            <br>";
                                                            echo "
                                                            <table>
                                                                <tr>
                                                                    <th>Product ID</th>
                                                                    <th>Product Name</th>
                                                                    <th>Quantity</th>
                                                                    <th>Unit Cost</th>
                                                                    <th>Total Cost</th>
                                                                </tr>";
                                                            $total_amount=0;
                                                            while ($row6=mysqli_fetch_array($res6)) {
                                                                echo "
                                                                <tr>
                                                                    <td>$row6[product_id]</td>
                                                                    <td>$row6[product_name]</td>
                                                                    <td>$row6[quantity]</td>
                                                                    <td>$row6[unit_cost]</td>
                                                                    <td>$row6[total_cost]</td>
                                                                </tr>";
                                                                $total_amount+=$row6["total_cost"];
                                                            }
                                                            echo "
                                                            </table><br><br>
                                                            Total Amount = $total_amount";
                                                        } else {
                                                            echo "No items in bill";
                                                        }
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    die("Error: ".mysqli_error($link));
                                                }
                                            } else {
                                                die("Error: ".mysqli_error($link));
                                            }
                                        } else {
                                            echo "Bill ID not found.";
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }    
                                } else if ($table== "customer") {
                                    $q3="SELECT fname, lname
                                        FROM customer
                                        WHERE phone_no='$id'";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3)==1) {
                                            $row3=mysqli_fetch_array($res3);
                                            $customer_name="$row3[fname] $row3[lname]";
                                            $q4="SELECT b.bill_id, date_issued, fname, lname
                                                FROM bill b
                                                INNER JOIN employee e
                                                ON b.employee_id=e.employee_id
                                                INNER JOIN bill_product bp
                                                ON b.bill_id=bp.bill_id
                                                WHERE customer_id=$id
                                                AND status='$status'
                                                AND b.branch_id=$_SESSION[branch_id]";
                                            if ($res4=mysqli_query($link, $q4)) {
                                                if (mysqli_num_rows($res4)>0) {
                                                    echo "Bills associated with $customer_name<br>
                                                    <table>
                                                        <tr>
                                                            <th>Bill ID</th>
                                                            <th>Date Issued</th>
                                                            <th>Employee</th>
                                                        </tr>";
                                                    while ($row4=mysqli_fetch_array($res4)) {   
                                                        echo "
                                                        <tr>
                                                            <td>$row4[bill_id]</td>
                                                            <td>$row4[date_issued]</td>
                                                            <td>$row4[fname] $row4[lname]</td>
                                                        </tr>";
                                                    }
                                                    echo "
                                                    </table>";
                                                } else {
                                                    echo "No bills associated with $customer_name were found.";
                                                }
                                            } else {
                                                die("Error: ".mysqli_error($link));
                                            }
                                        } else {
                                            echo "Customer not found.";
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                } else if ($table=="product") {
                                    $q3="SELECT product_name
                                        FROM product
                                        WHERE product_id=$id";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3)==1) {
                                            $row3=mysqli_fetch_array($res3);
                                            $product_name=$row3["product_name"];
                                            $q4="SELECT b.bill_id as bill_id, date_issued, 
                                                SUM(quantity) as quantity
                                                FROM bill_product bp
                                                INNER JOIN bill b
                                                ON bp.bill_id=b.bill_id
                                                WHERE product_id=$id
                                                AND branch_id=$_SESSION[branch_id]
                                                AND status='$status'
                                                GROUP BY product_id";
                                            if ($res4=mysqli_query($link, $q4)) {
                                                if (mysqli_num_rows($res4)>0) {
                                                    echo "Bills where $product_name is found.<br><br>";
                                                    echo "
                                                    <table>
                                                        <tr>
                                                            <th>Bill ID</th>
                                                            <th>Date Issued</th>
                                                            <th>Quantity</th>
                                                        </tr>";
                                                    while ($row4=mysqli_fetch_array($res4)) {
                                                        echo "
                                                        <tr>
                                                            <td>$row4[bill_id]</td>
                                                            <td>$row4[date_issued]</td>
                                                            <td>$row4[quantity]</td>
                                                            </tr>";
                                                    }
                                                } else {
                                                    echo "$product_name is not found in any of the bills.";
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