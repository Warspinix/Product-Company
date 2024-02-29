<?php
    session_start();
?>
<html>
    <head>
        <title>View Transaction</title>
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
                        <br><h1>View Transaction(s)</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <select name="criteria" required>
                                    <option value="" disable select>Search By</option>
                                    <option value="transaction_id=">Transaction ID</option>
                                    <option value="transaction_date=">Transaction Date (YYYY-MM-DD)</option>
                                    <option value="MONTH(transaction_date)=">Transaction Month (1-12)</option>
                                    <option value="total_amount<=">Amount - Upper Limit</option>
                                    <option value="total_amount>=">Amount - Lower Limit</option>
                                </select>
                            </div>
                            <div class="field">
                                <select name="status" required>
                                    <option value="" disable select>Successful or Not?</option>
                                    <option value="SUCCESSFUL">Successful</option>
                                    <option value="NOT SUCCESSFUL">Unsuccessful</option>
                                </select>
                            </div>
                            <div class="field">
                                <input type="text" name="value" required>
                                <label for="value">Search</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Search">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["value"])) {
                                $criteria=$_POST["criteria"];
                                $status=$_POST["status"];
                                $value=$_POST["value"];
                                $branch_id=$_SESSION["branch_id"];
                                $date=date("Y-m-d");
                                $q2="SELECT b_name, b_address, b_city, 
                                b_code, b_state, b_country
                                FROM company
                                WHERE branch_id=$branch_id";
                                if ($res2 = mysqli_query($link, $q2)) {
                                    $row2=mysqli_fetch_array($res2);
                                    $branch="$row2[b_name], $row2[b_address], 
                                    $row2[b_city] - $row2[b_code], $row2[b_state], $row2[b_country]";
                                    if ($criteria=="transaction_id=") {
                                        $heading="Transaction with Transaction ID ";
                                    } else if ($criteria=="transaction_date=") {
                                        $heading="Transaction(s) with transaction date as ";
                                        if ($value>$date)
                                            die("Invalid Date.");
                                    } else if ($criteria=="MONTH(transaction_date)=") {
                                        $heading="Transactions with transaction month as ";
                                        if (!is_numeric($value))    
                                            die("Invalid Month.");
                                        if ($value>12||$value<1)
                                            die("Invalid Month.");
                                    } else if ($criteria=="total_amount<=") {
                                        $heading="Transactions where the total amount is less than or equal to ";
                                        if ($value<0)
                                            die("Invalid Amount");
                                    } else if  ($criteria=="total_amount>=") {
                                        $heading="Transactions where the total mount is greater than or equal to ";
                                        if ($value<0)
                                            die("Invalid Amount");
                                    }
                                    $q3="SELECT transaction_id, t.bill_id as bill_id, 
                                        transaction_date, total_amount, 
                                        payment_method, t.status as status,
                                        c.fname as cfname, c.lname as clname,
                                        e.employee_id as employee_id, 
                                        e.fname as efname, e.lname as elname
                                        FROM transaction t
                                        INNER JOIN bill b
                                        on t.bill_id=b.bill_id
                                        INNER JOIN customer c
                                        ON b.customer_id=c.phone_no
                                        INNER JOIN employee e
                                        ON b.employee_id=e.employee_id
                                        WHERE $criteria$value
                                        AND status='$status'";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3)>0) {
                                            echo "
                                            <h4>$heading$value</h4>
                                            <table>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Bill ID</th>
                                                    <th>Date</th>
                                                    <th>Total Amount</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th>Customer Name</th>
                                                    <th>Employee ID</th>
                                                    <th>Employee Name</th>
                                                </tr>";
                                            while ($row3=mysqli_fetch_array($res3)) {
                                                echo "
                                                <tr>
                                                    <td>$row3[transaction_id]</td>
                                                    <td>$row3[bill_id]</td>
                                                    <td>$row3[transaction_date]</td>
                                                    <td>$row3[total_amount]</td>
                                                    <td>$row3[payment_method]</td>
                                                    <td>$row3[status]</td>
                                                    <td>$row3[cfname] $row3[clname]</td>
                                                    <td>$row3[employee_id]</td>
                                                    <td>$row3[efname] $row3[elname]</td>
                                                </tr>
                                            "; 
                                            }    
                                            echo "
                                            </table>";
                                        } else {
                                            echo "No transactions found.";
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