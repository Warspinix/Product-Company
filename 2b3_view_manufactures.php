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
            $q1="SELECT b_name, b_country 
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
                                        <li><a href='2c_transports.php'>'Transports</a></li>                                    
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
                                    <li><a href='2b1_view_production_details.php'>
                                        View Production Details
                                    </a></li>
                                    <li><a href='2b3_view_manufactures.php'>
                                        View Manufactures
                                    </a></li>
                                </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                    <li><a href='2b1_view_production_details.php'>
                                        View Production Details
                                    </a></li>
                                    <li><a href='2b2_update_production_details.php'>
                                        Update Production Details
                                    </a></li>
                                    <li><a href='2b3_view_manufactures.php'>
                                        View Manufactures
                                    </a></li>
                                </ul>";
                            } else {
                                echo "<ul>
                                    <li><a href='2b1_view_production_details.php'>
                                        View Production Details
                                    </a></li>
                                    <li><a href='2b2_update_production_details.php'>
                                        Update Production Details
                                    </a></li>
                                    <li><a href='2b3_view_manufactures.php'>
                                        View Manufactures
                                    </a></li>
                                </ul>";
                            }  
                        echo "
                            </div>";
                            $q2="SELECT manufacture_id, manufacture_date, project_name, product_name, quantity
                                FROM manufactures m
                                INNER JOIN project p
                                ON m.project_id=p.project_id
                                INNER JOIN product pb
                                ON m.product_id=pb.product_id
                                WHERE factory_id=$_SESSION[branch_id]
                                AND m.status='NOT SENT'";
                            if ($res2=mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2)>0) {    
                                    echo "
                                    <br>
                                    <h1>Completed Manufactures</h1>
                                    <br><br>
                                    <table>
                                        <tr>
                                            <th>Manufacture ID</th>
                                            <th>Project Name</th>
                                            <th>Product Name</th>
                                            <th>Manufacture Date</th>
                                            <th>Quantity</th>
                                        </tr>";
                                    while ($row2=mysqli_fetch_array($res2)) {
                                        echo "
                                        <tr>
                                            <td>$row2[manufacture_id]</td>
                                            <td>$row2[project_name]</td>
                                            <td>$row2[product_name]</td>
                                            <td>$row2[manufacture_date]</td>
                                            <td>$row2[quantity]</td>
                                        </tr>";
                                    }
                                    echo "
                                    </table>";
                                } else {
                                    echo "<br><h1>No manufactures found.</h1<";
                                }
                            } else {
                                die("<br>Error: ".mysqli_error($link));
                            }
                        echo "
                        </div>
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