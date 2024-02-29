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
                                    <li><a href='0d1_add_product.php'>
                                        Add Product
                                    </a></li>                            
                                    <li><a href='0d2_search_products.php'>
                                        Search Products
                                    </a></li> 
                                    <li><a href='0d3_update_product_details.php'>
                                        Update Product Details
                                    </a></li>        
                                    <li><a href='0d4_add_product_to_project.php'>
                                    Add Product to Project
                                </a></li>                                                                                                                                   
                                </ul>  
                            </div>";
                            ?>
                            <br><h1>Add Product to Project</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <input type="number" name="product_id" required>
                                    <label for="product_id">Product ID</label>
                                </div>
                                <div class="field">
                                    <input type="number" name="project_id" required>
                                    <label for="project_id">Project ID</label>
                                </div>                                
                                <div class="submit">
                                    <input type="submit" value="Add">
                                </div>  
                            <?php
                                if (isset($_POST["product_id"])) {
                                    $product_id=$_POST["product_id"];
                                    $project_id=$_POST["project_id"];
                                    $q2="SELECT product_name
                                        FROM product
                                        WHERE product_id=$product_id";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)==1) {
                                            $row2=mysqli_fetch_array($res2);
                                            $product_name=$row2["product_name"];
                                            $q3="SELECT project_name
                                                FROM project
                                                WHERE project_id=$project_id";                                            
                                            if ($res3=mysqli_query($link, $q3)) {
                                                if (mysqli_num_rows($res3)==1) {
                                                    $row3=mysqli_fetch_array($res3);
                                                    $project_name=$row3["project_name"];
                                                    $q4="SELECT *
                                                        FROM project_product
                                                        WHERE project_id=$project_id
                                                        AND product_id=$product_id";
                                                    if ($res4=mysqli_query($link, $q4)) {
                                                        if (mysqli_num_rows($res4)==1) {
                                                            $row4=mysqli_fetch_array($res4);
                                                            echo "$product_name has already been added to $project_name.";
                                                        } else {
                                                            $q5="INSERT INTO project_product (project_id, product_id)
                                                                VALUES ($project_id, $product_id)";
                                                            if (mysqli_query($link, $q5)) {
                                                                echo "$product_name has been successfully added to $project_name.";
                                                            } else {
                                                                 die("<br>Error: ".mysqli_error($link));
                                                            }
                                                        }
                                                    } else {
                                                        die("<br>Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    echo "Project ID not found";
                                                }
                                            } else {
                                                die("<br>Error: ".mysqli_error($link));
                                            }
                                        } else {
                                            echo "Product ID not found";
                                        }
                                    } else {
                                        die("<br>Error: ".mysqli_error($link));
                                    }
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