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
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4e_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4f_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                        <li><a href='4d_transport_products.php'>Transport Products</a></li>
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_transports.php'>View Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4f_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                        <li><a href='4d_transport_products.php'>Transport Products</a></li>
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
                        <div class='main'>";
                        ?>
                        <br><h1>View Transports</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <select name="io" required>
                                    <option value="" disable select>Incoming or Outgoing</option>
                                    <option value="incoming">Incoming</option>
                                    <option value="outgoing">Outgoing</option>
                                </select>
                            </div>
                            <div class="submit">
                                <input type="submit" value="View">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["io"])) {
                                $io=$_POST["io"];
                                if ($io=="incoming") {
                                    $q2="SELECT transport_id, p.product_id, product_name, quantity, b_name, b_address, b_city, b_state
                                        FROM transports t
                                        INNER JOIN manufactures m
                                        ON t.manufacture_id=m.manufacture_id
                                        INNER JOIN product p
                                        ON p.product_id=m.product_id
                                        INNER JOIN company
                                        ON source_branch_id=branch_id
                                        WHERE destination_branch_id=$_SESSION[branch_id]
                                        AND m.status='SENT'
                                        AND t.status='NOT RECEIVED'";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)>0) {
                                            echo "
                                            <br><h2>Ongoing Product Transports to be Received</h2>
                                            <table>
                                                <tr>
                                                    <th>Transport ID</th>
                                                    <th>Product ID</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Transported From</th>
                                                </tr>";
                                            while ($row2=mysqli_fetch_array($res2)) {
                                                echo "
                                                <tr>
                                                    <td>$row2[transport_id]</td>
                                                    <td>$row2[product_id]</td>
                                                    <td>$row2[product_name]</td>
                                                    <td>$row2[quantity]</td>
                                                    <td>$row2[b_name], $row2[b_address], $row2[b_city], $row2[b_state]</td>
                                                </tr>";
                                            }
                                            echo "</table>";
                                        } else {
                                            echo "No incoming transports.";
                                        }
                                    } else {
                                        die("<br><br>Error: ".mysqli_error($link));
                                    }   
                                } else if ($io=="outgoing") {
                                    $q3="SELECT warehouse_transport_id, product_name,
                                        destination_type, quantity, send_date
                                        FROM warehouse_transports wt
                                        INNER JOIN product pr
                                        ON wt.product_id=pr.product_id
                                        WHERE source_branch_id=$_SESSION[branch_id]
                                        AND status='NOT RECEIVED'
                                        ORDER BY warehouse_transport_id";
                                    if ($res3=mysqli_query($link, $q3)) {
                                        if (mysqli_num_rows($res3) > 0) {
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
                                            while ($row3=mysqli_fetch_array($res3)) {
                                                echo "
                                                <tr>
                                                    <td>$row3[warehouse_transport_id]</td>
                                                    <td>$row3[product_name]</td>
                                                    <td>$row3[quantity]</td>
                                                    <td>$row3[send_date]</td>";
                                                if ($row3["destination_type"]=="Showroom") {
                                                    $q4="SELECT b_name, b_address, b_city, b_state
                                                        FROM warehouse_transports
                                                        INNER JOIN company
                                                        ON destination_branch_id=branch_id
                                                        WHERE warehouse_transport_id=$row3[warehouse_transport_id]";
                                                    if ($res4=mysqli_query($link, $q4)) {
                                                        echo "
                                                        <td>$row4[b_name], $row4[b_address], $row4[b_city], $row4[b_state]</td>
                                                        <td>$row3[destination_type]</td>
                                                    </tr>";
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                } else if ($row2["destination_type"]=="Dealer") {
                                                    $q4="SELECT dealer_name, d_address, d_city, d_state
                                                        FROM warehouse_transports
                                                        INNER JOIN dealer
                                                        ON destination_dealer_id=dealer_id
                                                        WHERE warehouse_transport_id=$row3[warehouse_transport_id]";
                                                    if ($res4=mysqli_query($link, $q4)) {
                                                        echo "
                                                        <td>$row4[dealer_name], $row4[d_address], $row4[d_city], $row4[d_state]</td>
                                                        <td>$row3[destination_type]</td>
                                                    </tr>";
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                }
                                            }
                                            echo "
                                            </table>";
                                        } else {
                                            echo "No outgoing transports.";
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                }
                            }    
                        echo "</div>
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