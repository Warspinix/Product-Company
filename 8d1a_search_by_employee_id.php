<?php
    session_start();
?>
<html>
    <head>
        <title>Search Employee by ID</title>
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
                            <span style='font-size:15px; margin-top: 13px;'>$name, $country</span>
                            ";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul><br>
                                        <li><a href='8a_products.php'>Products</a></li>
                                        <li><a href='8b_customers.php'>Customers</a></li>
                                        <li><a href='8c_bill_transaction.php'>Bills and Transactions</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li<a href='8a_products.php'>Products</a></li>
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
                        <div class='main'>
                        <div class=top>";
                        if ($_SESSION["position"]=="Manager") {
                            echo "<ul>
                                <li><a href=8d1_search_employee.php>
                                Search Employee
                                </a></li>
                                <li><a href=8d2_add_employee.php>
                                Add Employee
                                </a></li>
                            </ul>";
                        }
                        else {
                            echo "<ul>
                                <li><a href='8d1_search_employee.php'>
                                Search Employee
                                </a></li>
                                <li><a href='8d2_add_employee.php'>
                                Add Employee
                                </a></li>
                                <li><a href='8d3_update_employee.php'>
                                Update Employee Details
                                </a></li>
                            </ul>";
                        }
                        echo "
                        </div>";
                        ?>
                        <br><h1>Search Employee by ID</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="text" name="employee_id" required>
                                <label for="employee_id">Employee ID</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Search">
                            </div>
                        <?php
                            if (isset($_POST["employee_id"])) {
                                $employee_id=$_POST["employee_id"];
                                $q2="SELECT *
                                    FROM employee
                                    WHERE employee_id=$employee_id
                                    AND branch_id=$_SESSION[branch_id]";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)> 0) {
                                        $row2=mysqli_fetch_array($res2);
                                        if ($row2["gender"]=="M")
                                            $gender="Male";
                                        else if ($row2["gender"]=="F")
                                            $gender="Female";
                                        else 
                                            $gender="Other";
                                        echo "<br>
                                        <table> 
                                        <tr>
                                            <th>Name</th> 
                                            <td>$row2[fname] $row2[lname]</td>
                                        </tr>
                                        <tr>
                                            <th>Gender</th>
                                            <td>$gender</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td>$row2[dob]</td>
                                        </tr>
                                        <tr>
                                            <th>Phone No(s)</th>
                                            <td>";
                                        $q3="SELECT COUNT(phone_no) as c
                                            FROM employee_phone_no
                                            WHERE employee_id=$_SESSION[id]";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)==1) {
                                                $row3=mysqli_fetch_array($res3);
                                                $count=$row3["c"];
                                                $q4="SELECT phone_no
                                                FROM employee_phone_no
                                                WHERE employee_id=$_SESSION[id]";
                                                if ($res4=mysqli_query($link, $q4)) {
                                                    if (mysqli_num_rows($res4)>0) {
                                                        $i=1;
                                                        while ($row4=mysqli_fetch_array($res4)) {
                                                            if ($i!=$count)
                                                                echo "$row4[phone_no], ";
                                                            else
                                                                echo "$row4[phone_no]";
                                                        }
                                                    } else {
                                                        echo "-";
                                                    }
                                                }
                                            } else {
                                                echo "-";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                        echo "</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Joining</th>
                                            <td>$row2[join_date]</td>
                                        </tr>
                                        <tr>
                                            <th>Position</th>
                                            <td>$row2[position]</td>
                                        </tr>
                                        <tr>
                                            <th>Salary</th>
                                            <td>$row2[salary]</td>
                                        </tr>
                                    </table>";
                                    } else {
                                        echo "Employee ID not found.";
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