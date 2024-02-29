<?php
    session_start();
?>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="style.css">
        <style>
            .field {
                display: inline-block;
                margin-right: 20px;
            }
        </style>
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
                                        <li><a href='0a1_add_branch.php'>
                                            Add Branch
                                        </a></li>                            
                                        <li><a href='0a2_search_branches.php'>
                                            Search Branches
                                        </a></li>                            
                                    </ul>
                            </div>";
                            ?>
                            <br><h1>Add Branch</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <select name="id" required>
                                        <option value="" disable select>Branch Type</option>
                                        <option value="141000">Design</option>
                                        <option value="142000">Production</option>
                                        <option value="143000">Factory</option>
                                        <option value="144000">Warehouse</option>
                                        <option value="145000">Store</option>
                                        <option value="146000">Operations</option>
                                        <option value="147000">Engineering</option>
                                        <option value="148000">Showroom</option>
                                        <option value="149000">Service</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="text" name="b_name" required>
                                    <label for="b_name">Branch Name</label>
                                </div>
                                <br>
                                <div class="field" style="width: 42%;">
                                    <input type="text" name="b_address" required>
                                    <label for="b_address">Branch Address</label>
                                </div>
                                <br><br>                        
                                <div class="field">
                                    <input type="text" name="b_city" required>
                                    <label for="b_city">Branch City</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="b_code" required>
                                    <label for="b_code">Branch Code</label>
                                </div>
                                <br>
                                <div class="field">
                                    <input type="text" name="b_state" required>
                                    <label for="b_state">Branch State</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="b_country" required>
                                    <label for="b_country">Branch Country</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Add Branch">
                                </div>
                            <?php
                                if (isset($_POST["b_country"])) {
                                    $id=$_POST["id"];
                                    $b_name=$_POST["b_name"];
                                    $b_address=$_POST["b_address"];
                                    $b_city=$_POST["b_city"];
                                    $b_code=$_POST["b_code"];
                                    $b_state=$_POST["b_state"];
                                    $b_country=$_POST["b_country"];
                                    $q2="SELECT MAX(branch_id) as branch_id
                                        FROM company
                                        WHERE branch_id-$id<100";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        $row2=mysqli_fetch_array($res2);
                                        $branch_id=$row2["branch_id"]+1;
                                        $q3="INSERT INTO company VALUES 
                                        ($branch_id, '$b_name', '$b_address',
                                        '$b_city', '$b_code', '$b_state', '$b_country')";
                                        if (mysqli_query($link, $q3)) {
                                            echo "Branch added.";
                                        } else {
                                            die("Error: ".mysqli_error($link));
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