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
                            <br><h1>Search Employee by Name</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <select name="criteria" required>
                                        <option value="" disable select>First or Last Name?</option>
                                        <option value="fname">First Name</option>
                                        <option value="lname">Last Name</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="text" name="value" required>
                                    <label for="value">Name</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Search">
                                </div>
                            <?php
                                if (isset($_POST["value"])) {
                                    $criteria=$_POST["criteria"];
                                    $value=$_POST["value"];
                                    $q2="SELECT employee_id, fname, lname, position
                                        FROM employee
                                        WHERE $criteria LIKE '%$value%'
                                        AND branch_id=$_SESSION[branch_id]";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)> 0) {
                                            echo "<br>
                                            <table> 
                                                <tr>
                                                    <th>Employee ID</th> 
                                                    <th>Name</th>
                                                    <th>Position</th>
                                                </tr>";
                                            while ($row2=mysqli_fetch_array($res2)) {
                                                echo "
                                                <tr>
                                                    <td>$row2[employee_id]</td>
                                                    <td>$row2[fname] $row2[lname]</td>
                                                    <td>$row2[position]</td>
                                                </tr>";
                                            }
                                            echo "                                        
                                            </table>";
                                        } else {
                                            echo "No matches found.";
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