<?php
    session_start();
?>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="style.css">
        <style>
            .field {
                display: inline-block;
                margin-right: 20px;
            }
        </style>
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
                            <div class='top'>
                                <ul>
                                    <li><a href='0e1_add_dealer.php'>
                                        Add Dealer
                                    </a></li>
                                    <li><a href='0e2_search_dealers.php'>
                                        Search Dealers
                                    </a></li>
                                </ul>  
                            </div>";
                            ?>
                            <br><h1>Add Dealer</h1>
                            <form method="POST">
                                <br>                                
                                <div class="field">
                                    <input type="text" name="dealer_name" required>
                                    <label for="d_name">Dealer Name</label>
                                </div>
                                <br>
                                <div class="field">
                                    <input type="number" name="contact_no" required>
                                    <label for="contact_no">Contact No</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="contact_mail" required>
                                    <label for="contact_mail">Contact Email</label>
                                </div>
                                <br>
                                <div class="field" style="width: 42%;">
                                    <input type="text" name="d_address" required>
                                    <label for="d_address">Dealer Address</label>
                                </div>
                                <br><br>                        
                                <div class="field">
                                    <input type="text" name="d_city" required>
                                    <label for="d_city">Dealer City</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="d_code" required>
                                    <label for="d_code">Dealer Code</label>
                                </div>
                                <br>
                                <div class="field">
                                    <input type="text" name="d_state" required>
                                    <label for="d_state">Dealer State</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="d_country" required>
                                    <label for="d_country">Dealer Country</label>
                                </div>
                                <div class="submit">
                                    <input type="submit" value="Add Dealer">
                                </div>
                            <?php
                                if (isset($_POST["d_country"])) {
                                    $dealer_name=$_POST["dealer_name"];
                                    $contact_no=strval($_POST["contact_no"]);
                                    $contact_mail=$_POST["contact_mail"];
                                    $d_address=$_POST["d_address"];
                                    $d_city=$_POST["d_city"];
                                    $d_code=$_POST["d_code"];
                                    $d_state=$_POST["d_state"];
                                    $d_country=$_POST["d_country"];  
                                    $q2="INSERT INTO dealer 
                                        (dealer_name, contact_no, contact_mail, d_address,
                                        d_city, d_code, d_state, d_country) VALUES 
                                        ('$dealer_name', '$contact_no', '$contact_mail', '$d_address',
                                        '$d_city', '$d_code', '$d_state', '$d_country')";                                  
                                    if (mysqli_query($link, $q2)) {
                                            echo "Dealer added.";                                    
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