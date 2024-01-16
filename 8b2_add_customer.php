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
                                <li><a href="8b1_search_customer.php">
                                   Search Customer
                                </a></li>
                                <li><a href="8b2_add_customer.php">
                                   Add Customer
                                </a></li>
                            </ul>
                        </div>
                        <br><h1>Add Customer</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="phone_no" required>
                                <label for="phone_no">Phone No</label>
                            </div>
                            <div class="field">
                                <input type="text" name="fname" required>
                                <label for="fname">First Name</label>
                            </div>
                            <div class="field">
                                <input type="text" name="lname" required>
                                <label for="lname">Last Name</label>
                            </div>
                            <div class="field">
                                <input type="email" name="email_id" required>
                                <label for="email_id">Email</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Add Customer">
                            </div>
                        </form>
                        <?php
                        if (isset($_POST["email_id"])) {
                            $phone_no=strval($_POST["phone_no"]);
                            $fname=$_POST["fname"];
                            $lname=$_POST["lname"];
                            $email_id=$_POST["email_id"];
                            $q2="SELECT * FROM customer
                                WHERE phone_no='$phone_no'";
                            if ($res2=mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2)==1) {
                                    $row2=mysqli_fetch_array($res2);
                                    echo "
                                    A customer with this phone number is already present.<br><br>
                                    <table>
                                        <tr>
                                            <th>Phone No</th>
                                            <td>$row2[phone_no]</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>$row2[fname] $row2[lname]</td>
                                        </tr>
                                    </table>";
                                } else {
                                    $q3="INSERT INTO customer (phone_no, fname, lname, email_id)
                                        VALUES ('$phone_no', '$fname', '$lname', '$email_id')";
                                    if (mysqli_query($link, $q3)) {
                                        echo "Customer added.";
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                }
                            } else {
                                die("Error: ".mysqli_error($link));
                            }
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