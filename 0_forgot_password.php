<?php
    session_start();
?>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="style_login.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="title">
                Forgot Password
            </div>
            <form method="POST">
                <div class="field">
                    <input type="text" name="email_id" required>
                    <label for="email_id">Email ID</label>
                </div>
                <div class="field">
                    <input type="password" name="p1" required>
                    <label for="password">New Password</label>
                </div>
                <div class="field">
                    <input type="password" name="p2" required>
                    <label for="password">Confirm New Password</label>
                </div>
                <br>
                <div class="field">
                    <input type="submit" value="Change Password">
                </div>
                <div>
                    &emsp;<a href="0_home.html">Go Home</a>
                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                    <a href="0_login.php">Login</a>
                </div>
        </form>
        <?php
            if(isset($_POST["p2"])) {
                $email_id=$_POST["email_id"];
                $employee_id=substr($email_id,0,8);
                $p1=$_POST["p1"];
                $p2=$_POST["p2"];
                $link = mysqli_connect("localhost","root","","product_company");
                if ($link==FALSE)
                    die("Error connecting to database. Please try again later.");
                if ($p1==$p2) {
                    $select="SELECT * FROM employee
                    WHERE employee_id='$employee_id'";
                    if ($result=mysqli_query($link, $select)) {
                        if (mysqli_num_rows($result)==1) {
                            $update="UPDATE employee
                                    SET password='$p1'
                                    WHERE employee_id='$employee_id'";
                            if (mysqli_query($link, $update)) {
                                echo "<div style='text-align: center'>
                                 Password successfully updated.
                                </div><br>";
                            } else {
                                echo "Unknown Error: ".mysqli_error($link); 
                            }
                        } else {
                            echo "<div style='text-align: center'>
                                Employee not found.
                            </div><br>"; 
                        }
                    } else {

                    }
                } else {
                    echo "<div style='text-align: center'>
                            Passwords don't match.
                        </div><br>";
                }
            }
        ?>
        </div>
    </body>
</html>