<?php
    session_start();
?>
<html>
    <head>
        <title>Update Employee Branch</title>
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
                        $branch_id=$_SESSION["branch_id"];
                        $employee_id=$_SESSION["employee_id"];
                        $join_date=date("Y-m-d");
                        $q2="SELECT b_name, b_address,
                            b_city, b_code, b_state, b_country
                            FROM company
                            WHERE branch_id=$branch_id";
                        if ($res2=mysqli_query($link, $q2)) {
                            $row2=mysqli_fetch_array($res2);
                            $new_branch="$row2[b_name], $row2[b_address], 
                                        $row2[b_city] - $row2[b_code], 
                                        $row2[b_state], $row2[b_country]";
                            $q3="SELECT b_name, b_address,
                                b_city, b_code, b_state, b_country
                                FROM employee e
                                INNER JOIN company c
                                ON e.branch_id=c.branch_id
                                WHERE employee_id='$employee_id'";
                            if ($res3=mysqli_query($link, $q3)) {
                                    $row3=mysqli_fetch_array($res3);
                                    $old_branch="$row3[b_name], $row3[b_address], 
                                                $row3[b_city] - $row3[b_code], 
                                                $row3[b_state], $row3[b_country]";
                                    $q4="UPDATE employee
                                        SET branch_id='$branch_id'
                                        WHERE employee_id='$employee_id'";
                                    if (mysqli_query($link, $q4)) {
                                        $q5="INSERT INTO employee_branch VALUES
                                            ('$employee_id', $branch_id, '$join_date')";
                                        if ($res5=mysqli_query($link, $q5)) {
                                            echo "<br><h1>Update Successful</h1><br>
                                                Old Branch: $old_branch<br>
                                                New Branch: $new_branch";
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
                            die("Error: ".mysqli_error($link));
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