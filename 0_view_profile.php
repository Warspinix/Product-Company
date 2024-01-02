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
            if ($link == FALSE) {
                die("<br><br>Error connecting to database. Please try again later.");
            }
            $previous_page = $_SERVER["HTTP_REFERER"];
            $q1 = "SELECT *
                    FROM company
                    WHERE branch_id=$_SESSION[branch_id]";
            $q2 = "SELECT fname, lname, position, phone_no, email_id, gender, dob, join_date, salary
                     FROM employee
                     WHERE employee_id='$_SESSION[id]'";
            if ($res1 = mysqli_query($link, $q1)) {
                $row1 = mysqli_fetch_array($res1);
                $branch = $row1["b_name"].", ".$row1["b_address"].", ".$row1["b_city"]." - ".$row1["b_code"]
                            .", ".$row1["b_state"].", ".$row1["b_country"];
                echo "<div class='container'>
                        <div class='left'><br><br>".
                            $row1["b_name"].", ".$row1["b_country"]."
                            <div class='profile-section'>
                                <br>
                                <div class='username'>
                                    ".$_SESSION['fname']." ".$_SESSION['lname']."
                                </div>
                                    <a href='$previous_page'><button class='edit-button'>Go Back</button></a>
                                    <a href='0_logout.php'><button class='logout'>Logout</button></a><br><br>
                            </div>
                        </div>
                        <div class='main'>";
                        if ($res2 = mysqli_query($link, $q2)) {
                            $row2 = mysqli_fetch_array($res2);
                            if ($row2["gender"]=="M")
                                $gender="Male";
                            else if ($row2["gender"]== "F")
                                $gender= "Female";
                            else 
                                $gender= "Other";
                            echo "<br><br><h1>Profile</h1><br><br>  
                                    <table> 
                                        <tr>
                                            <th>Name</th> 
                                            <td>$row2[fname] $row2[lname]</td>
                                        </tr>
                                        <tr>
                                            <th>Gender</th>
                                            <td>$gender</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td>$row2[dob]</td>
                                        </tr>
                                        <tr>
                                            <th>Phone No.</th>
                                            <td>$row2[phone_no]</td>
                                        </tr>
                                        <tr>
                                            <th>Email ID</th>
                                            <td>$row2[email_id]</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Joining</th>
                                            <td>$row2[join_date]</td>
                                        </tr>
                                        <tr>
                                            <th>Current Branch</th>
                                            <td>$branch</td>
                                        </tr>
                                        <tr>
                                            <th>Position</th>
                                            <td>$row2[position]</td>
                                        </tr>
                                        <tr>
                                            <th>Salary</th>
                                            <td>$row2[salary]</td>
                                        </tr>
                                    </table>
                                    ";  
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