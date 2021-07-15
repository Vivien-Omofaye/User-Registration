import cgi
from wsgiref.simple_server import make_server
import pymysql

html = """
        <!DOCTYPE html>
        <html>
            <head>
                <title>Registration Form</title>
            </head>
            <body>
            <h3>Registration Form</h3>
                <form action='' method='post'>
                    Title*: <select name='title'>
                            <option value='%s'></option>
                            <option value='Mr'>Mr</option>
                            <option value='Mrs'>Mrs</option>
                            <option value='Ms'>Ms</option>
                            <option value='Dr'>Dr</option>
                        </select>%s<br /><br />

                    First Name*:<input name='firstName' type='text' value='%s'/>%s<br /><br />

                    Last Name*:<input name='lastName' type='text' value='%s'/>%s<br /><br /> 

                    Street*:<input name='street' type='text' value='%s' />%s<br /><br /> 

                    City*:<input name='city' type='text' value='%s' />%s<br /><br /> 
                    
                    Province*:<input name='province' type='text' value='%s' />%s<br /><br /> 

                    Postal Code*:<input name='postalCode' type='text' value='%s' />%s<br /><br /> 

                    Country*: <select name='country'> 
                                <option value='%s'></option>
                                <option value='Canada'>Canada</option>
                                <option value='USA'>USA</option>
                            </select>%s<br /><br /> 

                    Phone*:<input name='phone' type='text' value='%s' />%s<br /><br /> 
                
                    Email*:<input name='email' type='text' value='%s' />%s<br /><br />

                    <input type='checkbox' name='newsSubs' value='', Newsletter subscription' />Newsletter subscription<br /><br />

                    <input type='submit' name='submit' value='Submit' />
                </form>
            </body>
        </html>
    """
 
def app(environ, start_response):
    response = html % ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')  

    if (environ['REQUEST_METHOD'] == 'POST'):
        #response = "You pressed submit"
        post_env = environ.copy()
        post_env['QUERY_STRING'] = ''
        post = cgi.FieldStorage(
            fp = environ['wsgi.input'],
            environ = post_env, 
            keep_blank_values = True
        )

        #get variables from post
        titleVar = post['title'].value
        firstNameVar = post['firstName'].value
        lastNameVar = post['lastName'].value
        streetVar = post['street'].value
        cityVar = post['city'].value
        provinceVar = post['province'].value
        postalCodeVar = post['postalCode'].value
        countryVar = post['country'].value
        phoneVar = post['phone'].value
        emailVar = post['email'].value

        #variables for each of the error messages
        titleVarError = ''
        firstNameVarError = ''
        lastNameVarError = ''
        streetVarError = ''
        cityVarError = ''
        provinceVarError = ''
        postalCodeVarError = ''
        countryVarError = ''
        phoneVarError = ''
        emailVarError = ''

        #error checking
        errors = 0
        if titleVar == '':
            errors = 1
            titleVarError = '<span><font color="red"><b>*required</b></font></span>'
        if firstNameVar == '':
            errors = 2
            firstNameVarError = '<span><font color="red"><b>*required</b></font></span>'
        if lastNameVar == '':
            errors = 3
            lastNameVarError = '<span><font color="red"><b>*required</b></font></span>'
        if streetVar == '':
            errors = 4
            streetVarError = '<span><font color="red"><b>*required</b></font></span>'
        if cityVar == '':
            errors = 5
            cityVarError = '<span><font color="red"><b>*required</b></font></span>'
        if provinceVar == '':
            errors = 6
            provinceVarError = '<span><font color="red"><b>*required</b></font></span>'
        if postalCodeVar == '':
            errors = 7
            postalCodeVarError = '<span><font color="red"><b>*required</b></font></span>'
        if countryVar == '':
            errors = 8
            countryVarError = '<span><font color="red"><b>*required</b></font></span>'
        if phoneVar == '':
            errors = 9
            phoneVarError = '<span><font color="red"><b>*required</b></font></span>'
        if emailVar == '':
            errors = 10
            emailVarError = '<span><font color="red"><b>*required</b></font></span>'

        if (errors != 0):
            response = html %(titleVar, titleVarError, firstNameVar, firstNameVarError, lastNameVar,
                              lastNameVarError, streetVar, streetVarError, cityVar, cityVarError,
                              provinceVar, provinceVarError, postalCodeVar, postalCodeVarError, 
                              countryVar, countryVarError, phoneVar, phoneVarError, emailVar, emailVarError)
        else:
            #insert data in database
            db = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db="pyuser_registration")
            cursor = db.cursor()

            sql = """INSERT INTO registered_users 
            (title, firstName, lastName, street, city, province, postalCode, country, phone, email)
            VALUES
            (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
            values = [titleVar, firstNameVar, lastNameVar, streetVar, cityVar, provinceVar, postalCodeVar, countryVar, phoneVar, emailVar]

            cursor.execute(sql, values)
            db.commit()

            #retrieve data from database
            cursor.execute("SELECT * FROM registered_users")
            data = cursor.fetchall()
            #print(data) 

            response = ""
            #display in table
            response += """
                            <html>
                                <head>
                                <title>Registered Users</title>
                                </head>
                                <body>
                                    <h3>Registered Users</h3>
                                    <table border='2' cellspacing='0' cellpadding='6'>
                                        <thead>
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
                                            </tr>
                                        </thead>
                        """ 
                        
            for row in data:
                response += "<tr>"
                user_id = "<td>%s</td>"%row[0]
                title_row = "<td>%s</td>"%row[1]
                first_name = "<td>%s</td>"%row[2]
                last_name = "<td>%s</td>"%row[3]
                street = "<td>%s</td>"%row[4]
                city = "<td>%s</td>"%row[5]
                province = "<td>%s</td>"%row[6]
                postal_code = "<td>%s</td>"%row[7]
                country = "<td>%s</td>"%row[8]
                phone_number = "<td>%s</td>"%row[9]
                email = "<td>%s</td>"%row[10]
                response += user_id
                response += title_row
                response += first_name
                response += last_name
                response += street
                response += city
                response += province
                response += postal_code
                response += country
                response += phone_number
                response += email
                response += "</tr>"
            response += """
                            </table>
                                </body>
                            </html>
                        """

            cursor.close()
            db.close()

            import gc
            gc.collect() 

    start_response('200 OK', [('Content-Type', 'text/html')])
    return [response.encode()]

if __name__ == '__main__':
    try:
        from wsgiref.simple_server import make_server
        httpd = make_server('', 8111, app)
        print('Serving on port 8111...')
        httpd.serve_forever()
    except KeyboardInterrupt:
        print('Goodbye...')
