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
                        <br><h1>View Services</h1>
                        <form method="POST">
                            <br>
                            <div class="field">
                                <select name="status" required>
                                    <option value="" disable select>Select Status</option>
                                    <option value="COMPLETE">Complete</option>
                                    <option value="INCOMPLETE">Incomplete</option>
                                </select>
                            </div>
                            <div class="submit">
                                <input type="submit" value="View">
                            </div>
                        </form>
                        <?php
                            if (isset($_POST["status"])) {
                                $status=$_POST["status"];
                                $employee_id=$_SESSION["id"];
                                $branch_id=$_SESSION["branch_id"];
                                $q2="SELECT service_id, product_name, customer_id, 
                                problem_description, start_date, deadline 
                                FROM services s 
                                INNER JOIN product p 
                                ON s.product_id=p.product_id 
                                WHERE employee_id='$employee_id'
                                AND branch_id=$branch_id
                                AND status='$status'";
                                if ($res2=mysqli_query($link, $q2)) {
                                    if (mysqli_num_rows($res2)>0) {
                                        echo "
                                        <table>
                                            <tr>
                                                <th>Service ID</th>
                                                <th>Product Name</th>
                                                <th>Customer Phone No</th>
                                                <th>Problem Description</th>
                                                <th>Start Date</th>
                                                <th>Deadline</th>
                                            </tr>";
                                        while ($row2=mysqli_fetch_array($res2)) {
                                            echo "
                                            <tr>
                                                <td>$row2[service_id]</td>
                                                <td>$row2[product_name]</td>
                                                <td>$row2[customer_id]</td>
                                                <td>$row2[problem_description]</td>
                                                <td>$row2[start_date]</td>
                                                <td>$row2[deadline]</td>
                                            </tr>";
                                        }
                                    } else {
                                        $status=strtolower($status);
                                        echo "No $status services found.";
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