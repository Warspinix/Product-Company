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
                                        <li><a href='1a_view_projects.php'>View Ongoing Projects</a></li>
                                        <li><a href=''></a></li>
                                        <li><a href=''></a></li>
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='1a_view_projects.php'>View Ongoing Projects</a></li>
                                        <li><a href='1b_view_employees.php'>View Employees</a></li>
                                        <li><a href=''></a></li>
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='1a_view_projects.php'>View Ongoing Projects</a></li>
                                        <li><a href='1b_view_employees.php'>View Employees</a></li>
                                        <li><a href='1_add_employees.php'>Add Employee to Project</a></li>
                                        <li><a href=''></a></li>
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
                        <div class='main'>";
                            $q2="SELECT p.project_id, project_name, pb.start_date, deadline
                                FROM project p
                                INNER JOIN project_branch pb
                                ON p.project_id=pb.project_id
                                INNER JOIN company c
                                ON pb.branch_id=c.branch_id
                                WHERE c.branch_id=$_SESSION[branch_id]";
                            if ($res2=mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2) > 0) {
                                    echo "
                                    <br>
                                    <h1>Ongoing Projects</h1>
                                    <br><br>
                                    <table>
                                        <tr>
                                            <th>Project ID</th>
                                            <th>Project Name</th>
                                            <th>Start Date in Branch</th>
                                            <th>Deadline</th>
                                        </tr>
                                    ";
                                    while ($row2=mysqli_fetch_array($res2)) {
                                        echo "
                                        <tr>
                                            <th>$row2[project_id]/th>
                                            <th>$row2[project_name]</th>
                                            <th>$row2[start_date]</th>
                                            <th>$row2[deadline]</th>
                                        </tr>
                                        ";
                                    }
                                    echo "
                                    </table>";
                                } else {
                                    echo "<br><h1>No ongoing projects.</h1>";
                                }
                            } else {
                                die("<br>Error: ".mysqli_error($link));
                            }           
                        echo "</div>
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