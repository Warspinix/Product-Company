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
                        ?>
                        <br><h1>Update Production Details</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="project_id" min="121001" max="129999" required>
                                <label for="project_id">Project ID</label>
                            </div>
                            <div class="field">                          
                                <input type="number" name="product_id" required>
                                <label for="product_id">Product ID</label>
                            </div>
                            <div class="field">
                                <input type="number" name="quantity" required>
                                <label for="quantity">Quantity</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Update">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["quantity"])) {
                                $project_id=$_POST["project_id"];
                                $product_id=$_POST["product_id"];
                                $quantity=$_POST["quantity"];
                                $manufacture_date=date("Y-m-d");
                                $q2="SELECT * FROM project
                                    WHERE project_id=$project_id";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2) == 1) {
                                        $q3="SELECT * FROM product 
                                            WHERE product_id=$product_id";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3) == 1) {
                                                $q3="SELECT * FROM project_product
                                                    WHERE project_id=$project_id
                                                    AND product_id=$product_id";
                                                if ($res3=mysqli_query($link, $q3)) {
                                                    if (mysqli_num_rows($res3) == 1) {
                                                        $q4="SELECT * FROM project_branch
                                                            WHERE project_id=$project_id
                                                            AND branch_id=$_SESSION[branch_id]
                                                            AND end_date IS NOT NULL";
                                                        if ($res4=mysqli_query($link, $q4)) {
                                                            if (mysqli_num_rows($res4) == 1) {
                                                                $q5="INSERT INTO manufactures
                                                                (project_id, product_id, manufacture_date, source_branch_id, quantity)
                                                                VALUES ($project_id, $product_id, $manufacture_date, $_SESSION[branch_id], $quantity)";
                                                                if (mysqli_query($link, $q5)) {
                                                                    echo "<br>Update Successful.";
                                                                } else {
                                                                    die("<br>Error: ".mysqli_error($link));  
                                                                }
                                                            } else {
                                                                echo "<br>This project is not a part of our branch.";
                                                            }
                                                        } else {
                                                            die("<br>Error: ".mysqli_error($link));  
                                                        }
                                                    } else {
                                                        echo "<br>Project ID and Product ID are not related.";
                                                    }
                                                } else {
                                                    die("<br>Error: ".mysqli_error($link));   
                                                }                                                
                                            } else {
                                                echo "<br>Product ID not found.";
                                            }
                                        } else {
                                            die("<br>Error: ".mysqli_error($link));
                                        }                                            
                                    } else {
                                        echo "<br>Project ID not found.";
                                    }
                                } else {
                                    die("<br>Error: ".mysqli_error($link));
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