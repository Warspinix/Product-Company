<?php
    session_start();
?>
<html>
    <head>
        <title>Incoming Transports</title>
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
                        <div class='left'><br>
                            <span style='font-size:15px; margin-top: 13px;'>$name, $country</span>";
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
                        <div class='top'>";
                        if ($_SESSION["position"]=="Regular") {
                            echo "<ul>
                                <li><a href='4a1_check_availability.php'>
                                    Check Product Availability
                                </a></li>
                                <li><a href='4a2_view_transports.php'>
                                    View Incoming Product Transports
                                </a></li>
                                <li><a href='4a3_receive_products.php'>
                                    Receive Products
                                </a></li>                               
                            </ul>";
                        } else if ($_SESSION["position"]=="Manager") {
                            echo "<ul>
                                <li><a href='4a1_check_availability.php'>
                                    Check Product Availability
                                </a></li>
                                <li><a href='4a2_view_transports.php'>
                                    View Incoming Product Transports
                                </a></li>
                                <li><a href='4a3_receive_products.php'>
                                    Receive Products
                                </a></li>                               
                            </ul>";
                        } else {
                            echo "<ul>
                                <li><a href='4a1_check_availability.php'>
                                    Check Product Availability
                                </a></li>
                                <li><a href='4a2_view_transports.php'>
                                    View Incoming Product Transports
                                </a></li>
                                <li><a href='4a3_receive_products.php'>
                                    Receive Products
                                </a></li>                               
                            </ul>";
                        }  
                        echo "
                        </div>";
                        $q2="SELECT transport_id, 
                            m.manufacture_id as manufacture_id, 
                            product_name, 
                            t.quantity as quantity,
                            manufacture_date, 
                            b_name, b_address, b_city, b_state, b_country
                            FROM transports t
                            INNER JOIN manufactures m
                            ON t.manufacture_id=m.manufacture_id
                            INNER JOIN product p
                            ON p.product_id=m.product_id
                            INNER JOIN company
                            ON factory_id=branch_id
                            WHERE warehouse_id=$_SESSION[branch_id]
                            AND t.status='NOT RECEIVED'";
                        if ($res2=mysqli_query($link, $q2)) {
                            if (mysqli_num_rows($res2)>0) {
                                echo "
                                <br><h2>Ongoing Product Transports to be Received</h2>
                                <table>
                                    <tr>
                                        <th>Transport ID</th>
                                        <th>Manufacture ID</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Manufacture Date</th>
                                        <th>Transported From</th>
                                    </tr>";
                                while ($row2=mysqli_fetch_array($res2)) {
                                    echo "
                                    <tr>
                                        <td>$row2[transport_id]</td>
                                        <td>$row2[product_id]</td>
                                        <td>$row2[product_name]</td>
                                        <td>$row2[quantity]</td>
                                        <td>$row2[manufacture_date]</td>
                                        <td>$row2[b_name], $row2[b_address], $row2[b_city], $row2[b_state], $row2[b_country]</td>
                                    </tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<br><h1>No incoming transports.</h1>";
                            }
                        } else {
                            die("<br><br>Error: ".mysqli_error($link));
                        }   
                        echo "
                        </div>
                    </div>
                ";
            } else {
                die("<br><br>Error: ".mysqli_error($link));
            }
        } else {
            echo "<br><br><div style='text-align:center;'><h1>You aren't logged in.</h1><br>
                    <a href='0_home.html'><button class='edit-button'>Go Home</button></a>&emsp;
                    <a href='0_login.php'><button class='edit-button'>Login</button></a></div><br><br>";
        }
    ?>
    </body>
</html>