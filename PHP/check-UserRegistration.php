<?php

    //check for the registration form submission
    if ( isset( $_GET['submit'] ) ) {

        //retrieve the values passed from registration form
        $title = $_GET ['title'];
        $firstName = $_GET ['firstName'];
        $lastName = $_GET ['lastName'];
        $street = $_GET ['street'];
        $city = $_GET ['city'];
        $province = $_GET ['province'];
        $postCode = $_GET ['postCode'];
        $country = $_GET ['country'];
        $phone = $_GET ['phone'];
        $email = $_GET ['email'];
        //check checkbox entry
        if (!empty($_GET['newsSubs'])) {
            $newsLetter = "Yes";
        } else {
            if (empty($_GET['newsSubs'])) {
                $newsLetter = "No";
            }
        }

        //check for errors
        $errors = 0;

        if ( empty( $title ) ) $errors = 1;
        if ( empty( $firstName ) ) $errors = 2; 
        if ( empty( $lastName ) ) $errors = 3;
        if ( empty( $street ) ) $errors = 4;
        if ( empty( $city ) ) $errors = 5; 
        if ( empty( $province ) ) $errors = 6;
        if ( empty( $postCode ) ) $errors = 7;
        if ( empty( $country ) ) $errors = 8;
        if ( empty( $phone ) ) $errors = 9;
        if ( empty( $email ) ) $errors = 10;

        //if errors, redisplay the form with values and error message(s)
        if ( $errors !=0 ) {


?>


<!DOCTYPE html>
<html>
    <head>
        <title>Registration Form</title>
    </head>
    <body>
        <h3>Registration Form</h3>
        <form action='check-UserRegistration.php' method='get'> 
            Title*: <select name='title'>
                        <option value=''></option>
                        <option value='Mr'
                        <?php if ($title == 'Mr') echo " selected "; ?>
                        >Mr</option>
                        <option value='Mrs'
                        <?php if ($title == 'Mrs') echo " selected "; ?>
                        >Mrs</option>
                        <option value='Ms'
                        <?php if ($title == 'Ms') echo " selected "; ?>
                        >Ms</option> 
                        <option value='Dr'
                        <?php if ($title == 'Dr') echo " selected "; ?>
                        >Dr</option>
                    </select><br />
                    <?php if ( empty( $title ) ) echo "<span style='color:red;'>*Title required</span>";?>
                    <br />

            First Name*:<input name='firstName' type='text' value='<?php echo $firstName;?>'/><br />
            <?php if ( empty( $firstName ) ) echo "<span style='color:red;'>*First name required</span>";?>
            <br />

            Last Name*:<input name='lastName' type='text' value='<?php echo $lastName;?>'/><br />
            <?php if ( empty( $lastName ) ) echo "<span style='color:red;'>*Last name required</span>";?>
            <br />

            Street*:<input name='street' type='text' value='<?php echo $street;?>' /><br />
            <?php if ( empty( $street ) ) echo "<span style='color:red;'>*Street required</span>";?>
            <br />

            City*:<input name='city' type='text' value='<?php echo $city;?>' /><br />
            <?php if ( empty( $city ) ) echo "<span style='color:red;'>*City required</span>";?>
            <br />
            
            Province*:<input name='province' type='text' value='<?php echo $province;?>' /><br />
            <?php if ( empty( $province ) ) echo "<span style='color:red;'>*Province required</span>";?>
            <br />

            Postal Code*:<input name='postCode' type='text' value='<?php echo $postCode;?>' /><br />
            <?php if ( empty( $postCode ) ) echo "<span style='color:red;'>*Postal Code required</span>";?>
            <br />

            Country*: <select name='country'> 
                        <option value=''></option>
                        <option value='Canada'
                        <?php if ($country == 'Canada') echo " selected "; ?>
                        >Canada</option>
                        <option value='USA'
                        <?php if ($country == 'USA') echo " selected "; ?>
                        >USA</option>
                    </select><br />
                    <?php if ( empty( $country ) ) echo "<span style='color:red;'>*Country required</span>";?>
                    <br />

            Phone*:<input name='phone' type='text' value='<?php echo $phone;?>' /><br />
            <?php if ( empty( $phone ) ) echo "<span style='color:red;'>*Phone number required</span>";?>
            <br />

            Email*:<input name='email' type='text' value='<?php echo $email;?>' /><br />
            <?php if ( empty( $email ) ) echo "<span style='color:red;'>*Email required</span>";?>
            <br />

            <input type='checkbox' name='newsSubs[]' value='Newsletter subscription' />Newsletter subscription<br /><br />

            <input name='submit' type='submit' value='Submit' />
        </form>
    </body>
</html>

<?php
        //if no errors, send data to database
        } else {
            //establish a connection to a database
            $conn = mysqli_connect("localhost", "root", "", "user_registration");
            if (!$conn) {
                die( "connection failed:" . mysqli_connect_error() );
            }

            //add data to 'user_registration' table
            $sql = "INSERT INTO registered_users (title, firstName, lastName, street, city, province, postCode, country, phone, email, newsLetterSubs)
            VALUES ('$title', '$firstName', '$lastName','$street', '$city', '$province', '$postCode', '$country', '$phone','$email', '$newsLetter')";
            mysqli_query($conn, $sql);

            //retrieve all entries from user_registration
            $sql = "SELECT * FROM registered_users";
            $results = mysqli_query($conn, $sql);
            
            //retrieve and display all data from database onto HTML table
            echo "<table border='2' cellspacing='0' cellpadding='6'>
            <tr>
            <th>User_ID</th>
            <th>Title</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Street</th>
            <th>City</th>
            <th>Province</th>
            <th>Postal Code</th>
            <th>Country</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Newsletter subscription</th>
            </tr>";

            while( $row = mysqli_fetch_assoc($results) ) {
                echo "<tr>";
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td>" . $row["title"] . "</td>"; 
                echo "<td>" . $row["firstName"] . "</td>";
                echo "<td>" . $row["lastName"] . "</td>";
                echo "<td>" . $row["street"] . "</td>";
                echo "<td>" . $row["city"] . "</td>";
                echo "<td>" . $row["province"] . "</td>";
                echo "<td>" . $row["postCode"] . "</td>";
                echo "<td>" . $row["country"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["newsLetterSubs"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            //close database connection
            mysqli_close($conn);
        }

    //if not from form submission, redirect back to form
    } else {
        "Location: assignment3.php";
    }
?>




