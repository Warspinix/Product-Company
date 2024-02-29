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
                                        <li><a href='1a_projects.php'>Projects</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='1a_projects.php'>Projects</a></li>
                                        <li><a href='1b_employees.php'>Employees</a></li>                                                                            
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='1a_projects.php'>Projects</a></li>
                                        <li><a href='1b_employees.php'>Employees</a></li>                                        
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
                                <li><a href='1b1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href=1b3_add_employee.php>
                                    Add Employee
                                </a></li>
                            </ul>";
                        }
                        else {
                            echo "<ul>
                                <li><a href='1b1_view_employees.php'>
                                    View All Employees
                                </a></li>
                                <li><a href='1b2_search_employees.php'>
                                    Search Employee
                                </a></li>
                                <li><a href='1b3_add_employee.php'>
                                    Add Employee
                                </a></li>
                                <li><a href='1b4_update_employee.php'>
                                    Update Employee Details
                                </a></li>
                            </ul>";
                        }
                        echo "
                            </div>";
                            $q2="SELECT employee_id, fname, lname, position, 
                                gender, dob, join_date, salary
                                FROM employee
                                WHERE branch_id=$_SESSION[branch_id]";
                            if ($res2=mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2) > 0) {
                                    echo "
                                    <br>
                                    <h1>Employees</h1>
                                    <br><br>
                                    <table>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Date of Birth</th>
                                            <th>Gender</th>
                                            <th>Join Date</th>
                                            <th>Salary</th>
                                        </tr>
                                    ";
                                    while ($row2=mysqli_fetch_array($res2)) {
                                        if ($row2["gender"]=="M")
                                            $gender="Male";
                                        else if ($row2["gender"]=="F")
                                            $gender= "Female";
                                        else 
                                            $gender= "Other";
                                        echo "
                                        <tr>
                                            <td>$row2[employee_id]</td>
                                            <td>$row2[fname] $row2[lname]</td>
                                            <td>$row2[position]</td>
                                            <td>$row2[dob]</td>
                                            <td>$gender</td>
                                            <td>$row2[join_date]</td>
                                            <td>$row2[salary]</td>
                                        </tr>";
                                    }
                                    echo "
                                    </table>";
                                } else {
                                    echo "<br><h1>No employees in the branch.</h1>";
                                }
                            } else {
                                die("<br>Error: ".mysqli_error($link));
                            }           
                        echo "</div>
                    </div>
                ";
            } else {
                die("<br>Error: ".mysqli_error($link));
            }
        } else {
            echo "<br><br><div style='text-align:center;'><h1>You aren't logged in.</h1><br>
                    <a href='0_home.html'><button class='edit-button'>Go Home</button></a>&emsp;
                    <a href='0_login.php'><button class='edit-button'>Login</button></a></div><br><br>";
        }
    ?>
    </body>
</html>