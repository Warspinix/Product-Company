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
                                echo "<ul>
                                        <li><a href='9a_service.php'>Service</a></li>                                        
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul>
                                        <li><a href='9a_service.php'>Service</a></li>
                                        <li><a href='9b_employees.php'>Employees</a></li>                                        
                                    </ul>";
                            } else {
                                echo "<ul>
                                        <li><a href='9a_service.php'>Service</a></li>
                                        <li><a href='9b_employees.php'>Employees</a></li>                                        
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
                        <div class='top'>
                            <ul>
                                <li><a href='9a1_search_products.php'>
                                    Search Products
                                </a></li>
                                <li><a href='9a2_search_customers.php'>
                                    Search Customers
                                </a></li>
                                <li><a href='9a3_add_new_service.php'>
                                    Add New Service
                                </a></li>    
                                <li><a href='9a4_view_services.php'>
                                    View Services
                                </a></li>    
                                <li><a href='9a5_update_service.php'>
                                    Update Service
                                </a></li>
                            </ul>
                        </div> ";
                        ?>
                        <br><h1>Add New Service</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <input type="number" name="product_id" required>
                                <label for="product_id">Product ID</label>
                            </div>
                            <div class="field">
                                <input type="text" name="customer_id" required>
                                <label for="customer_id">Customer Phone No</label>
                            </div>
                            <div class="field">
                                <textarea name="problem_description" maxlength="255" required></textarea>
                                <label for="problem_description">Problem Description</label>
                            </div>
                            <br><br><br><br><br>
                            <div class="field">
                                <input type="date" name="deadline" required>
                                <label for="deadline">Service Deadline</label>
                            </div>
                            <div class="submit">
                                <input type="submit" value="Add">
                            </div>
                        <?php
                            if (isset($_POST["deadline"])) {
                                $product_id=$_POST["product_id"];
                                $customer_id=$_POST["customer_id"];
                                $problem_description=$_POST["problem_description"];
                                $deadline=$_POST["deadline"];
                                $start_date=date("Y-m-d");
                                $employee_id=$_SESSION["id"];
                                $branch_id=$_SESSION["branch_id"];                                
                                $q2="SELECT product_name
                                    FROM product
                                    WHERE product_id='$product_id'";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)==1) {
                                        $row2=mysqli_fetch_array($res2);
                                        $product_name=$row2["product_name"];
                                        $q3="SELECT fname, lname
                                            FROM customer
                                            WHERE phone_no='$customer_id'";
                                        if ($res3=mysqli_query($link, $q3)) {
                                            if (mysqli_num_rows($res3)==1) {
                                                $row3=mysqli_fetch_array($res3);
                                                $customer_name="$row3[fname] $row3[lname]";
                                                $q4="INSERT INTO services
                                                    (product_id, customer_id, employee_id, problem_description,
                                                    branch_id, start_date, deadline) VALUES
                                                    ($product_id, '$customer_id', '$employee_id', '$problem_description',
                                                    '$branch_id', '$start_date', '$deadline')";
                                                if (mysqli_query($link, $q4)) {
                                                    echo "Service Added.";
                                                } else {
                                                    die("Error: ".mysqli_error($link));
                                                }
                                            } else {
                                                echo "Product ID doesn't exist.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                    } else {
                                        echo "Product ID doesn't exist.";
                                    }
                                } else {
                                    die("Error: ".mysqli_error($link));
                                }
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