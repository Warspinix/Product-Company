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
                                    <li><a href='2c1_transport_products.php'>
                                        Transport Products
                                    </a></li>
                                </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                    <li><a href='2c1_transport_products.php'>
                                        Transport Products
                                    </a></li>
                                    <li><a href='2c2_view_transports.php'>
                                        View Transports
                                    </a></li>
                                </ul>";
                            } else {
                                echo "<ul>
                                    <li><a href='2c1_transport_products.php'>
                                        Transport Products
                                    </a></li>
                                    <li><a href='2c2_view_transports.php'>
                                        View Transports
                                    </a></li>
                                </ul>";
                            }  
                        echo "
                            </div>";
                        $q2="SELECT transport_id, 
                            m.manufacture_id as manufacture_id, 
                            p.product_name as product_name, 
                            t.quantity as quantity, 
                            manufacture_date,
                            warehouse_id, 
                            b_name, b_address, b_city, b_state, b_country
                            FROM transports t
                            INNER JOIN manufactures m
                            ON t.manufacture_id=m.manufacture_id
                            INNER JOIN product p
                            ON m.product_id=p.product_id
                            INNER JOIN company c
                            ON warehouse_id=branch_id
                            WHERE factory_id=$_SESSION[branch_id]
                            AND t.status='NOT RECEIVED'";
                        if ($res2=mysqli_query($link, $q2)) {
                            if (mysqli_num_rows($res2)>0) {
                                echo "
                                <br><h1>Transports</h1><br>
                                <table>
                                    <tr>
                                        <th>Transport ID</th>
                                        <th>Manufacture ID</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Manufacture Date</th>
                                        <th>Destination Warehouse ID</th>
                                        <th>Destination Warehouse Name</th>
                                    </tr>";
                                while ($row2=mysqli_fetch_array($res2)) {
                                    echo "
                                    <tr>
                                        <td>$row2[transport_id]</td>
                                        <td>$row2[manufacture_id]</td>
                                        <td>$row2[product_name]</td>
                                        <td>$row2[quantity]</td>
                                        <td>$row2[manufacture_date]</td>
                                        <td>$row2[warehouse_id]</td>
                                        <td>$row2[b_name], $row2[b_address], $row2[b_city], $row2[b_state], $row2[b_country]</td>
                                    </tr>";
                                }
                            } else {
                                echo "<br><h1>No transports found.</h1>";
                            }
                        } else {
                            die("Error: ".mysqli_error($link));
                        }
            }
        } else {
            echo "<br><br><div style='text-align:center;'><h1>You aren't logged in.</h1><br>
                    <a href='0_home.html'><button class='edit-button'>Go Home</button></a>&emsp;
                    <a href='0_login.php'><button class='edit-button'>Login</button></a></div><br><br>";
        }
    ?>
    </body>
</html>