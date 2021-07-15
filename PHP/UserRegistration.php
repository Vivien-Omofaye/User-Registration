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
                        <option value='Mr'>Mr</option>
                        <option value='Mrs'>Mrs</option>
                        <option value='Ms'>Ms</option>
                        <option value='Dr'>Dr</option>
                    </select><br /><br />

            First Name*:<input name='firstName' type='text' value=''/><br /><br />

            Last Name*:<input name='lastName' type='text' value=''/><br /><br />

            Street*:<input name='street' type='text' value='' /><br /><br />

            City*:<input name='city' type='text' value='' /><br /><br />
            
            Province*:<input name='province' type='text' value='' /><br /><br />

            Postal Code*:<input name='postCode' type='text' value='' /><br /><br />

            Country*: <select name='country'> 
                        <option value=''></option>
                        <option value='Canada'>Canada</option>
                        <option value='USA'>USA</option>
                    </select><br /><br />

            Phone*:<input name='phone' type='text' value='' /><br /><br />

            Email*:<input name='email' type='text' value='' /><br /><br />

            <input type='checkbox' name='newsSubs[]' value='Newsletter subscription' />Newsletter subscription<br /><br />

            <input name='submit' type='submit' value='Submit' />
        </form>
    </body>
</html>

