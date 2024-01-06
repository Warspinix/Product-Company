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
                Login
            </div>
            <form method="POST">
                <br>
                <div class="field">
                    <input type="text" name="id" minlength="8" maxlength="8" required>
                    <label for="employee_id">Employee ID</label>
                </div>
                <div class="field">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                </div>
                <br>
                <div class="field">
                    <input type="submit" value="Login">
                </div>
                <div class="text">
                    &emsp;<a href="0_home.html">Go Home</a>
                    &emsp;&emsp;&emsp;&emsp;<a href="0_forgot_password.php">Forgot Password?</a>
                </div>
        </form>
        <?php
            if(isset($_POST["password"])) {
                $id=$_POST["id"];
                $password = $_POST["password"];
                $link = mysqli_connect("localhost","root","","product_company");
                if ($link == FALSE)
                    die("Error connecting to database. Please try again later.");
                $select = "SELECT * FROM employee
                            WHERE employee_id='$id'";
                if ($result = mysqli_query($link, $select)) {
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        if ($row["password"]==$password) {
                            $_SESSION["id"] = $row["employee_id"];
                            $_SESSION["fname"] = $row["fname"];
                            $_SESSION["lname"] = $row["lname"];
                            $_SESSION["position"] = $row["position"];
                            $_SESSION["branch_id"] = $row["branch_id"];
                            if (substr($_SESSION["id"],0,3) == "131")
                                header("Location: 1_design.php");
                            elseif (substr($_SESSION["id"],0,3) == "132")
                                header("Location: 2_production.php");
                            elseif (substr($_SESSION["id"],0,3) == "133")
                                header("Location: 3_finance.php");
                            elseif (substr($_SESSION["id"],0,3) == "134")
                                header("Location: 4_storage.php");
                            elseif (substr($_SESSION["id"],0,3) == "135")
                                header("Location: 5_marketing.php");
                            elseif (substr($_SESSION["id"],0,3) == "136")
                                header("Location: 6_testing.php");
                            elseif (substr($_SESSION["id"],0,3) == "137")
                                header("Location: 7_software.php");
                            elseif (substr($_SESSION["id"],0,3) == "138")
                                header("Location: 8_sales.php");
                            elseif (substr($_SESSION["id"],0,3) == "139")
                                header("Location: 9_service.php");
                        } else {
                            echo "<div class='text' style='text-align: center'>
                                    Incorrect Password.
                                </div><br>";
                        }
                    } else {
                        echo "<div class='text' style='text-align: center;'>
                                You aren't registered.
                            </div><br>";
                    }    
                } else {
                    echo "Unknown Error: ".mysqli_error($link);
                }
            }
        ?>
        </div>
    </body>
</html>