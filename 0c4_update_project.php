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
                            <br><h1>Update Project</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <input type="number" name="project_id" required>
                                    <label for="project_id">Project ID</label>
                                </div>
                                <div class="field">
                                    <select name="status" required>
                                        <option value="" disable select>Status</option>
                                        <option value="IN PROGRESS">In Progress</option>
                                        <option value="ON HOLD">On Hold</option>
                                        <option value="ENDED">Ended</option>
                                    </select>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="End Project">
                                </div>
                            </form>
                            <?php
                                if (isset($_POST["project_id"])) {
                                    $project_id=$_POST["project_id"];
                                    $status=$_POST["status"];
                                    $end_date=date("Y-m-d");
                                    $q2="SELECT project_name, status
                                        FROM project
                                        WHERE project_id=$project_id";
                                    if ($res2=mysqli_query($link, $q2)) {
                                        if (mysqli_num_rows($res2)==1) {
                                            $row2=mysqli_fetch_array($res2);
                                            $project_name=$row2["project_name"];
                                            $current_status=$row2["status"];
                                            if ($current_status=="ENDED") {
                                                echo "$project_name is already over.";
                                            } else {
                                                $project_name=$row2["project_name"];
                                                if ($status=="IN PROGRESS" || $status=="ON HOLD") {
                                                    $q3="UPDATE project
                                                        set status='$status'
                                                        WHERE project_id=$project_id";
                                                } else if ($status=="ENDED") {
                                                    $q3="UPDATE project
                                                        set end_date='$end_date',
                                                        status='$status'
                                                        WHERE project_id=$project_id";
                                                }
                                                if (mysqli_query($link, $q3)) {
                                                    $q4="UPDATE project_branch
                                                        set end_date='$end_date'
                                                        WHERE project_id=$project_id";
                                                    if (mysqli_query($link, $q4)) {
                                                        echo "Update Successful.";
                                                    } else {
                                                        die("Error: ".mysqli_error($link));
                                                    }
                                                } else {
                                                    die("Error: ".mysqli_error($link));
                                                }
                                            }
                                        } else {
                                            echo "Project ID doesn't exist";
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