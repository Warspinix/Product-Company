<?php
    session_start();
?>
<html>
    <head>
        <title>Update Employee Name</title>
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
                                        <li><a href='4a_products.php'>Products</a></li>
                                        <li><a href='4b_transports.php'>Transports</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='4a_products.php'>Products</a></li>
                                        <li><a href='4b_transports.php'>Transports</a></li>
                                        <li><a href='4c_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='4a_products.php'>Products</a></li>
                                        <li><a href='4b_transports.php'>Transports</a></li>
                                        <li><a href='4c_employees.php'>Employees</a></li>                                        
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
                                <li><a href='4c1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href=4c3_add_employee.php>
                                    Add Employee
                                </a></li>
                            </ul>";
                        }
                        else {
                            echo "<ul>
                                <li><a href='4c1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href='4c2_search_employees.php'>
                                    Search Employee
                                </a></li>
                                <li><a href='4c3_add_employee.php'>
                                    Add Employee
                                </a></li>
                                <li><a href='4c4_update_employee.php'>
                                    Update Employee Details
                                </a></li>
                            </ul>";
                        }
                        echo "
                            </div>";
                        ?>
                        <br><h1>Update Employee Name</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="text" name="fname" required>
                                <label for="fname">New First Name</label>
                            </div>
                            <div class="field">
                                <input type="text" name="lname" required>
                                <label for="lname">New Last Name</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Update">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["lname"])) {
                                $employee_id=$_SESSION["employee_id"];
                                $fname=$_POST["fname"];
                                $lname=$_POST["lname"];
                                $ef=strtolower($fname);
                                $email_id="$employee_id.$ef@apple.com";
                                $q2="UPDATE employee
                                    SET fname='$fname',
                                    lname='$lname',
                                    email_id='$email_id'
                                    WHERE employee_id='$employee_id'";
                                if (mysqli_query($link, $q2)) {
                                    echo "Update Successful.";
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