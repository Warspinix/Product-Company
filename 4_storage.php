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
                        <div class='left'><br><br>
                            $name, $country";
                            if ($_SESSION["position"]=="Regular") {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_ongoing_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4e_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                    </ul>";
                            } else if ($_SESSION["position"]=="Manager") {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_ongoing_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4f_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                        <li><a href='4d_transport_products.php'>Transport Products</a></li>
                                    </ul>";
                            } else {
                                echo "<ul><br>
                                        <li><a href='4a_check_availability.php'>Check Availabilty</a></li>
                                        <li><a href='4b_view_ongoing_transports.php'>View Ongoing Transports</a></li>
                                        <li><a href='4e_view_showrooms.php'>View Showrooms</a></li>
                                        <li><a href='4f_view_dealers.php'>View Dealers</a><li>
                                        <li><a href='4c_receive_products.php'>Receive Products</a></li>
                                        <li><a href='4d_transport_products.php'>Transport Products</a></li>
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
                            $q2 = "SELECT * FROM company";
                            if ($res2=mysqli_query($link, $q2)) {
                                if (mysqli_num_rows($res2) > 0) {
                                    while ($row2=mysqli_fetch_array($res2)) {

                                    }
                                } else {
                                    echo "<br><h1>No</h1>";
                                }
                            } else {
                                die("<br><br>Error: ".mysqli_error($link));
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