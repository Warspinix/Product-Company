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
                            <ul><br>
                                <li><a href='0a_branches.php'>Branches</a></li>
                                <li><a href='0b_spares_and_suppliers.php'>Spares and Suppliers</a></li>
                                <li><a href='0c_projects.php'>Projects</a></li>
                                <li><a href='0d_products.php'>Products</a></li>
                                <li><a href='0e_dealer.php'>Dealers</a></li>
                            </ul>";                           
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
                            <div class=top>                            
                                <ul>
                                    <li><a href='0b1_add_spare.php'>
                                        Add Spare
                                    </a></li>                            
                                    <li><a href='0b2_search_spares.php'>
                                        Search Spares
                                    </a></li>
                                    <li><a href='0b6_remove_spare.php'>
                                        Remove Spare
                                    </a></li>
                                    <li><a href='0b3_add_supplier.php'>
                                        Add Supplier
                                    </a></li>                            
                                    <li><a href='0b4_search_suppliers.php'>
                                        Search Suppliers
                                    </a></li>  
                                    <li><a href='0b7_remove_supplier.php'>
                                        Remove Supplier
                                    </a></li>  
                                    <li><a href='0b5_link_spare_and_supplier.php'>
                                        Link Spare and Supplier
                                    </a></li>  
                                    <li><a href='0b8_view_supplier_details.php'>
                                        View Supplier Details
                                    </a></li>                                                                                  
                                </ul>  
                            </div>";
                            ?>
                            <br><h1>Add Spare</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <input type="text" name="part_name" required>
                                    <label for="part_name">Part Name</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Add">
                                </div>
                            </form>
                            <?php
                                if (isset($_POST["part_name"])) {
                                    $part_name=$_POST["part_name"];
                                    $q2="SELECT spare_id, part_name
                                        FROM spares
                                        WHERE part_name='$part_name'";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)>0) {
                                            echo "This part is already present.";
                                        } else {
                                            $q3="INSERT INTO spares (part_name)
                                                VALUES ('$part_name')";
                                            if (mysqli_query($link, $q3)) {
                                                echo "Spare successfully added.";
                                            } else {
                                                die("Error: ".mysqli_error($link));
                                            }
                                        }
                                    } else {
                                        die("Error: ".mysqli_error($link));
                                    }
                                }
                            echo "
                        </div>     
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