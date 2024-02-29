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
                            <br><h1>Create New Project</h1>
                            <form method="POST">
                                <br>
                                <div class="field">
                                    <select name="id" required>
                                        <option value="" disable select>Project Type</option>
                                        <option value="121000">iPhone</option>
                                        <option value="122000">iPad</option>
                                        <option value="123000">MacBook</option>
                                        <option value="124000">Apple Watch</option>
                                        <option value="125000">HomePod</option>
                                        <option value="126000">AirPods</option>
                                        <option value="127000">Mac</option>
                                        <option value="128000">Apple Pencil</option>
                                        <option value="129000">Other Accessories</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <input type="text" name="project_name" required>
                                    <label for="project_name">Project Name</label>
                                </div>
                                <div class="field">
                                    <input type="date" name="deadline" required>
                                    <label for="deadline">Deadline</label>
                                </div>
                                <div class="field">
                                    <input type="number" name="allocated_budget" required>
                                    <label for="allocated_budgjet">Budget</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Create">
                                </div>
                            </form>
                            <?php
                                if (isset($_POST["allocated_budget"])) {
                                    $id=$_POST["id"];
                                    $project_name=$_POST["project_name"];
                                    $deadline=$_POST["deadline"];
                                    $allocated_budget=$_POST["allocated_budget"];
                                    $start_date=date("Y-m-d");
                                    if ($deadline>$start_date) {
                                        if ($allocated_budget>0) {
                                            $q2="SELECT MAX(project_id) as project_id
                                                FROM project
                                                WHERE project_id-$id<100";
                                            if ($res2=mysqli_query($link, $q2)) {
                                                $row2=mysqli_fetch_array($res2);
                                                $project_id=$row2["project_id"]+1;
                                                $q3="INSERT INTO project
                                                    (project_id, project_name, start_date, deadline, allocated_budget)
                                                    VALUES
                                                    ($project_id, '$project_name', '$start_date', '$deadline', $allocated_budget)";
                                                if (mysqli_query($link, $q3)) {
                                                    echo "Project created.";
                                                } else {
                                                    die("Error: ".mysqli_error($link));
                                                }
                                            } else {
                                                die("Error: ".mysqli_error($link));
                                            }
                                        } else {
                                            echo "Invalid Budget.";
                                        }
                                    } else {
                                        echo "Invalid Deadline.";
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