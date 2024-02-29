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
                            <span style='font-size:15px; margin-top: 13px;'>$name, $country</span>
                            ";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul>
                                        <li><a href='9a_service.php'>Service</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                        <li><a href='9a_service.php'>Service</a></li>
                                        <li><a href='9b_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            } else {
                                echo "<ul>
                                        <li><a href='9a_service.php'>Service</a></li>
                                        <li><a href='9b_employees.php'>Employees</a></li>                                        
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
                                <li><a href='9b1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href='9b3_add_employee.php'>
                                    Add Employee
                                </a></li>
                            </ul>";
                        }
                        else {
                            echo "<ul>
                                <li><a href='9b1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href='9b2_search_employees.php'>
                                    Search Employee
                                </a></li>
                                <li><a href='9b3_add_employee.php'>
                                    Add Employee
                                </a></li>
                                <li><a href='9b4_update_employee.php'>
                                    Update Employee Details
                                </a></li>
                            </ul>";
                        }
                        echo "
                            </div>";
                            ?>
                            <br><h1>Add Employee</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <input type="text" name="fname" required>
                                    <label for="fname">First Name</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="lname" required>
                                    <label for="lname">Last Name</label>
                                </div>  
                                <div class="field">
                                    <input type="number" name="phone_no" required>
                                    <label for="phone_no">Phone No</label>
                                </div>
                                <div class="field">
                                    <select name="gender" required>
                                        <option value="" disable select>Gender</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                        <option value="O">Other</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="date" name="dob" required>
                                    <label for="dob">Date of Birth</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Add Employee">
                                </div>
                            </form>
                            <?php
                                function generateRandomPassword() {
                                    $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                    $password='';
                                    for ($i = 0;$i<15; $i++) {
                                        $index=rand(0, strlen($characters)-1);
                                        $password.=$characters[$index];
                                    }
                                    return $password;
                                }
                                if (isset($_POST["dob"])) {
                                    $password=generateRandomPassword();
                                    $fname=$_POST["fname"];
                                    $lname=$_POST["lname"];
                                    $phone_no=$_POST["phone_no"];
                                    $gender=$_POST["gender"];
                                    $dob=$_POST["dob"];
                                    $join_date=date("Y-m-d");
                                    $salary=20000;
                                    $branch_id=$_SESSION["branch_id"];
                                    $q2="SELECT MAX(employee_id) as employee_id
                                        FROM employee
                                        WHERE employee_id LIKE '139%'";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)==1) {
                                            $row2=mysqli_fetch_array($res2);
                                            $employee_id=strval((int)$row2["employee_id"]+1);
                                            $ef=strtolower($fname);
                                            $email_id="$employee_id.$ef@apple.com";
                                            $q3="INSERT INTO employee
                                                (employee_id, password, fname, lname,
                                                email_id, gender, dob, 
                                                join_date, salary, branch_id) VALUES
                                                ('$employee_id', '$password', '$fname', '$lname',
                                                '$email_id', '$gender', '$dob', '$join_date', $salary, $branch_id)";
                                            if (mysqli_query($link, $q3)) {
                                                $q4="INSERT INTO employee_phone_no VALUES
                                                    ($employee_id, $phone_no)";
                                                if (mysqli_query($link, $q4)) {
                                                    $q5="INSERT INTO employee_branch VALUES
                                                        ('$employee_id', $branch_id, '$join_date')";
                                                    if (mysqli_query($link, $q5)) {
                                                        echo "Employee added.";
                                                    } else {
                                                        $error=mysqli_error($link);
                                                        $q6="DELETE FROM employee
                                                            WHERE employee_id=$employee_id";
                                                        if (mysqli_query($link, $q6)) {
                                                            $q7="DELETE FROM employee_phone_no
                                                                WHERE employee_id='$employee_id'";
                                                            if (mysqli_query($link, $q7)) {
                                                                die("Error: $error");
                                                            } else {
                                                                die("Error: ".mysqli_error($link));
                                                            }
                                                        } else {
                                                            die("Error: ".mysqli_error($link));
                                                        }
                                                    }
                                                } else {
                                                    $error=mysqli_error($link);
                                                    $q8="DELETE FROM employee
                                                        WHERE employee_id=$employee_id";
                                                    if (mysqli_query($link, $q8)) {
                                                        die("Error: $error");
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
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