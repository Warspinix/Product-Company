<?php
    session_start();
?>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="login_style.css">
    </head>
    <body>
        <h1>Login</h1>
        <div class=
        <form method="POST">
            <input type="text" name="id" placeholder="Employee ID" required>
            <br><br>
            <input type="password" name="password" placeholder="Password" required>
            <br><br>
            <input type="submit" value="Login">
        </form>
        <?php
            if(isset($_POST["password"])) {
                $id=$_POST["id"];
                $password = $_POST["password"];
                $link = mysqli_connect("localhost","root","","product_company");
                if ($link == FALSE) {
                    die("Error connecting to database. Please try again later.");
                }
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
                            echo "<br>Incorrect Password<br>";
                        }
                    } else {
                        echo "<br>You aren't registered.<br>";
                    }    
                } else {
                    echo "Unknown Error: ".mysqli_error($link);
                }
            }
        ?>
    </body>
</html>