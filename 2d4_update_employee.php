<?php
    session_start();
?>
<html>
    <head>
        <title>Update Employee</title>
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
                                        <li><a href='2a_spares.php'>Spares</a></li>                                        
                                        <li><a href='2b_manufacturing.php'>Manufacturing</a></li>
                                        <li><a href='2c_transports.php'>Transports</a></li>                                    
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='2a_spares.php'>Spares</a></li>                                        
                                        <li><a href='2b_manufacturing.php'>Manufacturing</a></li>
                                        <li><a href='2c_transports.php'>Transports</a></li>
                                        <li><a href='2d_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='2a_spares.php'>Spares</a></li>                                        
                                        <li><a href='2b_manufacturing.php'>Manufacturing</a></li>
                                        <li><a href='2c_transports.php'>Transports</a></li>
                                        <li><a href='2d_employees.php'>Employees</a></li>                                        
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
                                <li><a href='2d1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href=2d3_add_employee.php>
                                    Add Employee
                                </a></li>
                            </ul>";
                        }
                        else {
                            echo "<ul>
                                <li><a href='2d1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href='2d2_search_employees.php'>
                                    Search Employee
                                </a></li>
                                <li><a href='2d3_add_employee.php'>
                                    Add Employee
                                </a></li>
                                <li><a href='2d4_update_employee.php'>
                                    Update Employee Details
                                </a></li>
                            </ul>";
                        }
                        echo "
                        </div>";
                        ?>
                        <br><h1>Update Employee Details</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="text" name="employee_id" required>
                                <label for="employee_id">Employee ID</label>
                            </div>
                            <div class="field">
                                <select name="criteria" required>
                                    <option value="" disable select>Update?</option>
                                    <option value="name">Name</option>
                                    <option value="position">Position</option>
                                    <option value="salary">Salary</option>
                                    <option value="branch">Branch</option>
                                </select>   
                            </div>
                            <div class="submit">
                                <input type="submit" value="Next">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["criteria"])) {
                                $employee_id=$_POST["employee_id"];
                                $q2="SELECT *
                                    FROM employee
                                    WHERE employee_id='$employee_id'
                                    AND branch_id=$_SESSION[branch_id]";
                                if ($res2 = mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $criteria=$_POST["criteria"];
                                        $_SESSION["employee_id"]=$employee_id;
                                        if ($employee_id==$_SESSION["id"]) {
                                            echo "You aren't allowed to change your own details.";
                                        } else {
                                            if ($criteria=="name")
                                                header("Location: 2d4a_update_employee_name.php");
                                            else if ($criteria=="position")
                                                header("Location: 2d4b_update_employee_position.php");
                                            else if ($criteria=="salary")
                                                header("Location: 2d4c_update_employee_salary.php");
                                            else if ($criteria=="branch")
                                                header("Location: 2d4d_update_employee_branch.php");
                                        }
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