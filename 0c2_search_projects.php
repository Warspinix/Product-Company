<?php
    session_start();
?>
<html>
    <head>
        <title>Project</title>
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
                                    <li><a href='0c1_create_project.php'>
                                        Create New Project
                                    </a></li>                            
                                    <li><a href='0c2_search_projects.php'>
                                        Search Projects
                                    </a></li> 
                                    <li><a href='0c3_add_project_to_branch.php'>
                                        Add Project to Branch
                                    </a></li>                            
                                    <li><a href='0c4_update_project.php'>
                                        Update Project
                                    </a></li>                                                                               
                                </ul>  
                            </div>";
                            ?>
                            <br><h1>Search Project</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <select name="status" required>
                                        <option value="" disable select>Status</option>
                                        <option value="IN PROGRESS">In Progress</option>
                                        <option value="ON HOLD">On Hold</option>
                                        <option value="ENDED">Ended</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="text" name="project_name" required>
                                    <label for="project_name">Search Project</label>
                                </div>      
                                <div class="submit">
                                    <input type="submit" value="Search">
                                </div>                         
                            <?php
                                if (isset($_POST["project_name"])) {
                                    $status=$_POST["status"];
                                    $project_name=$_POST["project_name"];
                                    if ($status=="IN PROGRESS" || $status=="ON HOLD") {
                                        $q2="SELECT project_id, project_name,
                                            start_date, deadline, allocated_budget
                                            FROM project
                                            WHERE project_name LIKE '%$project_name%'
                                            AND status='$status'";
                                        if ($res2=mysqli_query($link, $q2)) {
                                            if (mysqli_num_rows($res2)>0) {
                                                echo "
                                                <table>
                                                    <tr>
                                                        <th>Project ID</th>
                                                        <th>Project Name</th>
                                                        <th>Start Date</th>
                                                        <th>Deadline</th>
                                                        <th>Allocated Budget</th>
                                                    </tr>";
                                                while ($row2=mysqli_fetch_array($res2)) {
                                                    echo "
                                                    <tr>
                                                        <td>$row2[project_id]</td>
                                                        <td>$row2[project_name]</td>
                                                        <td>$row2[start_date]</td>
                                                        <td>$row2[deadline]</td>
                                                        <td>$row2[allocated_budget]</td>
                                                    </tr>";
                                                }
                                                echo "</table>";
                                            } else {
                                                echo "No projects found.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }
                                    } else if ($status=="ENDED") {
                                        $q2="SELECT project_id, project_name,
                                        start_date, deadline, end_date,
                                        allocated_budget
                                        FROM project
                                        WHERE project_name LIKE '%$project_name%'
                                        AND status='$status'";
                                        if ($res2=mysqli_query($link, $q2)) {
                                            if (mysqli_num_rows($res2)>0) {
                                                echo "
                                                <table>
                                                    <tr>
                                                        <th>Project ID</th>
                                                        <th>Project Name</th>
                                                        <th>Start Date</th>
                                                        <th>Deadline</th>
                                                        <th>End Date</th>
                                                        <th>Allocated Budget</th>
                                                    </tr>";
                                                while ($row2=mysqli_fetch_array($res2)) {
                                                    echo "
                                                    <tr>
                                                        <td>$row2[project_id]</td>
                                                        <td>$row2[project_name]</td>
                                                        <td>$row2[start_date]</td>
                                                        <td>$row2[deadline]</td>
                                                        <td>$row2[end_date]</td>
                                                        <td>$row2[allocated_budget]</td>
                                                    </tr>";
                                                }
                                                echo "</table>";
                                            } else {
                                                echo "No projects found.";
                                            }
                                        } else {
                                            die("Error: ".mysqli_error($link));
                                        }                                        
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