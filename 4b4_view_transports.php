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
                                <li><a href='4b1_view_showrooms.php'>
                                    View Showrooms
                                </a></li>
                                <li><a href='4b2_view_dealers.php'>
                                    View Dealers
                                </a></li>
                                <li><a href='4b3_transport_products.php'>
                                    Transport Products
                                </a></li>                                                           
                            </ul>";
                        } else if ($_SESSION["position"]=="Manager") {
                            echo "<ul>
                                <li><a href='4b1_view_showrooms.php'>
                                    View Showrooms
                                </a></li>
                                <li><a href='4b2_view_dealers.php'>
                                    View Dealers
                                </a></li>
                                <li><a href='4b3_transport_products.php'>
                                    Transport Products
                                </a></li>  
                                <li><a href='4b4_view_transports.php'>
                                    View Outgoing Product Transports
                                </a></li>                               
                            </ul>";
                        } else {
                            echo "<ul>
                                <li><a href='4b1_view_showrooms.php'>
                                    View Showrooms
                                </a></li>
                                <li><a href='4b2_view_dealers.php'>
                                    View Dealers
                                </a></li>
                                <li><a href='4b3_transport_products.php'>
                                    Transport Products
                                </a></li>
                                <li><a href='4b4_view_transports.php'>
                                    View Outgoing Product Transports
                                </a></li>                                 
                            </ul>";
                        }  
                    echo "
                        </div>";
                        $q2="SELECT warehouse_transport_id, product_name,
                        destination_type, quantity, send_date
                        FROM warehouse_transports wt
                        INNER JOIN manufactures m
                        ON wt.manufacture_id=m.manufacture_id
                        INNER JOIN product pr
                        ON m.product_id=pr.product_id
                        WHERE warehouse_id=$_SESSION[branch_id]
                        AND status='NOT RECEIVED'
                        ORDER BY warehouse_transport_id";
                    if ($res2=mysqli_query($link, $q2)) {
                        if (mysqli_num_rows($res2) > 0) {
                            echo "
                            <br><h2>Ongoing Product Transports to be Received</h2>
                            <table>
                                <tr>
                                    <th>Transport ID</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Transported To</th>
                                    <th>Type</th>
                                </tr>";
                            while ($row2=mysqli_fetch_array($res2)) {
                                echo "
                                <tr>
                                    <td>$row2[warehouse_transport_id]</td>
                                    <td>$row2[product_name]</td>
                                    <td>$row2[quantity]</td>
                                    <td>$row2[send_date]</td>";
                                if ($row2["destination_type"]=="showroom") {
                                    $q3="SELECT b_name, b_address, b_city, b_state
                                        FROM warehouse_transports
                                        INNER JOIN company
                                        ON destination_branch_id=branch_id
                                        WHERE warehouse_transport_id=$row2[warehouse_transport_id]";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3)==1) {
                                            $row3=mysqli_fetch_array($res3);
                                            $destination_type=ucfirst($row2["destination_type"]);
                                            echo "
                                            <td>$row3[b_name], $row3[b_address], $row3[b_city], $row3[b_state]</td>
                                            <td>$destination_type</td>
                                        </tr>";
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                } else if ($row2["destination_type"]=="dealer") {
                                    $q3="SELECT dealer_name, d_address, d_city, d_state
                                        FROM warehouse_transports
                                        INNER JOIN dealer
                                        ON destination_dealer_id=dealer_id
                                        WHERE warehouse_transport_id=$row2[warehouse_transport_id]";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3)==1) {
                                            $row3=mysqli_fetch_array($res3);
                                            $destination_type=ucfirst($row2["destination_type"]);
                                            echo "
                                            <td>$row3[dealer_name], $row3[d_address], $row3[d_city], $row3[d_state]</td>
                                            <td>$destination_type</td>                                          
                                        </tr>";
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                }
                            }
                            echo "
                            </table>";
                        } else {
                            echo "<br><h1>No outgoing transports.</h1>";
                        }
                    } else {
                        die("Error: ".mysqli_error($link));
                    }
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