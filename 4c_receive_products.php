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
                        <div class='main'>
                        <br><h1>Receive Products</h1>";
                        ?>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="transport_id" min=1 required>
                                <label for="transport_id">Transport ID</label>
                            </div>
                            <div class="field">
                                <input type="submit" value="Receive">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["transport_id"])) {
                                $transport_id=$_POST["transport_id"];
                                $date= date("Y-m-d");
                                $q2="SELECT destination_branch_id
                                    FROM transports
                                    WHERE transport_id=$transport_id";
                                if ($res2 = mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2) == 1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $destination_branch_id=$row2["destination_branch_id"];
                                        $q3="SELECT p.product_id, product_name, b_name, b_address, b_city, 
                                        b_code, b_state, b_country, quantity, manufacture_date 
                                        FROM transports t
                                        INNER JOIN product p
                                        ON t.product_id=p.product_id
                                        INNER JOIN company c
                                        ON t.source_branch_id=c.branch_id
                                        INNER JOIN manufactures m
                                        ON t.manufacture_id=m.manufacture_id
                                        WHERE transport_id=$transport_id
                                        AND destination_branch_id=$destination_branch_id";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)==1) {
                                                $row3=mysqli_fetch_array($res3);
                                                echo "
                                                <table>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <td>$row3[product_name]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>From</th>
                                                        <td>$row3[b_name], $row3[b_address], $row3[b_city] - $row3[b_code], 
                                                        $row3[b_state], $row3[b_country]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Quantity</th>
                                                        <td>$row3[quantity]</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Manufacture Date</th>
                                                        <td>$row3[manufacture_date]</td>                                    
                                                    </tr>
                                                </table>";
                                                $q4="SELECT product_stock
                                                    FROM warehouse
                                                    WHERE branch_id=$_SESSION[branch_id]
                                                    AND product_id=$row3[product_id]";
                                                if ($res4=mysqli_query($link, $q4)) {
                                                    if (mysqli_num_rows($res4)==1) {
                                                    $row4=mysqli_fetch_array($res4);
                                                    $new_quantity=$row3["quantity"];
                                                    $actual_quantity=$row4["quantity"];
                                                    $q5="UPDATE warehouse
                                                        SET product_stock=$actual_quantity+$new_quantity
                                                        WHERE branch_id=$_SESSION[branch_id]
                                                        AND product_id=$row3[product_id]";
                                                    } else {
                                                        $q5="INSERT INTO warehouse VALUES
                                                            ($_SESSION[branch_id],$row3[product_id],$row3[quantity])";
                                                    }
                                                    if (mysqli_query($link, $q5)) {
                                                        $q6="UPDATE transports
                                                            SET receive_date=$date,
                                                            status='RECEIVED'
                                                            WHERE transport_id=$transport_id";
                                                        if (mysqli_query($link, $q6)) {
                                                            echo "$quantity units of $row3[product_name] successfully received.";
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
                                                echo "<br>This transport is not associated with this branch.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        } 
                                    } else {
                                        echo "<br>Transport ID not found.";
                                    }
                                } else {
                                    die("Error: ".mysqli_error($link));
                                }   
                            }          
                        echo "</div>
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