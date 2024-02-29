<?php
    session_start();
?>
<html>
    <head>
        <title>Production</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <?php
        if(isset($_SESSION["id"])) {
            $link = mysqli_connect("localhost","root","","product_company");
            if ($link == FALSE) {
                die("<br><br>Error connecting to database. Please try again later.");
            }
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
                                <div class='username'>
                                    <br>
                                    ".$_SESSION['fname']." ".$_SESSION['lname']."
                                </div>
                                    <a href='0_view_profile.php'><button class='edit-button'>View Profile</button></a>
                                    <a href='0_logout.php'><button class='logout'>Logout</button></a><br>
                            </div>
                        </div>
                        <div class='main'>
                            <div class='top'>";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul>
                                    <li><a href='2a1_view_all_spares.php'>
                                        Search Spares
                                    </a></li>
                                    <li><a href='2a2_check_availability_of_spares.php'>
                                        Check Availability of Spares
                                    </a></li>
                                    <li><a href='2a3_view_orders.php'>
                                        View Orders
                                    </a></li>
                                    <li><a href='2a4_log_supplies.php'>
                                        Log Supplies
                                    </a></li>
                                    <li><a href='2a6_update_usage_of_spares.php'>
                                        Update Usage of Spares
                                    </a></li>                                    
                                </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                    <li><a href='2a1_view_all_spares.php'>
                                        Search Spares
                                    </a></li>
                                    <li><a href='2a2_check_availability_of_spares.php'>
                                        Check Availability of Spares
                                    </a></li>
                                    <li><a href='2a3_view_orders.php'>
                                        View Orders
                                    </a></li>
                                    <li><a href='2a4_log_supplies.php'>
                                        Log Supplies
                                    </a></li>
                                    <li><a href='2a6_update_usage_of_spares.php'>
                                        Update Usage of Spares
                                    </a></li>
                                    <li><a href='2a7_view_usage_of_spares.php'>
                                        View Usage of Spares
                                    </a></li>
                                </ul>";
                            } else {
                                echo "<ul>
                                <li><a href='2a1_view_all_spares.php'>
                                    Search Spares
                                </a></li>
                                <li><a href='2a2_check_availability_of_spares.php'>
                                    Check Availability of Spares
                                </a></li>
                                <li><a href='2a3_view_orders.php'>
                                    View Orders
                                </a></li>
                                <li><a href='2a4_log_supplies.php'>
                                    Log Supplies
                                </a></li>
                                <li><a href='2a5_make_orders.php'>
                                    Make Orders
                                </a></li>
                                <li><a href='2a6_update_usage_of_spares.php'>
                                    Update Usage of Spares
                                    </a></li>
                                <li><a href='2a7_view_usage_of_spares.php'>
                                    View Usage of Spares
                                </a></li>
                            </ul>";
                            }
                        echo "
                            </div>";
                            ?>
                            <br><h1>Search Spares</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <input type="text" name="part_name" required>
                                    <label for="part_name">Part Name</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Search">
                                </div>
                            </form>
                            <?php
                            if (isset($_POST["part_name"])) {
                                $part_name=$_POST["part_name"];
                                $q2="SELECT spare_id, part_name
                                    FROM spares
                                    WHERE part_name LIKE '%$part_name%'";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)>0) {
                                        echo "
                                        <table>
                                            <tr>
                                                <th>Spare ID</th>
                                                <th>Part Name</th>
                                            </tr>";
                                        while ($row2=mysqli_fetch_array($res2)) { 
                                            echo "
                                            <tr>
                                                <td>$row2[spare_id]</td>
                                                <td>$row2[part_name]</td>
                                            </tr>";
                                        }
                                        echo "
                                        </table>";
                                    } else {
                                        echo "No parts found.";
                                    }
                                } else {
                                    die("Error: ".mysqli_error($link));
                                }
                            }
                        echo "</table>
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