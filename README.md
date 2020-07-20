# suzuki-motors
URL : http://localhost/suzuki-motors/api/

x-api-key `AQLlSvDbvCAI9a!uduCy_FgCNcWNsV8oiEUe`

## Table of contents

1. **Customers**
	+ [Registration](#registration)
	+ [Login](#login)



## Customers

### Registration
POST `api/customers/registration/`   

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data 
|----------------|----------|-----------|-----------------------|-----------------------
| fullname        |  yes     |  varchar      |        -              |  Elline Ocampo
| email_address        |  yes     |  varchar      |        -              |  edocampo@myoptimind.com
| password       |  yes     |  varchar |      |  testing
| mobile_number       |  yes     |  varchar |      |  09497912581


##### Response
```javascript
201 OK
{
    "data": {
        "fullname": "Elline Ocampo",
        "email_address": "edocampo@myoptimind.com",
        "password": "testing",
        "mobile_number": "09497912581"
    },
    "meta": {
        "message": "Registration Successful",
        "status": 201
    }
}
```

### Login
POST `api/customers/login/`   

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data 
|----------------|----------|-----------|-----------------------|-----------------------
| email_address        |  yes     |  varchar      |        email or phone number         |  edocampo@myoptimind.com or 09497912581
| password       |  yes     |  varchar |      |  testing


##### Response
```javascript
200 OK
{
    "data": {
        "id": "1",
        "fullname": "Elline Ocampo",
        "email_address": "edocampo@myoptimind.com",
        "mobile_number": "09497912581",
        "created_at": "2020-06-29 16:13:15",
        "updated_at": "0000-00-00 00:00:00"
    },
    "meta": {
        "message": "User login successfully",
        "status": "200"
    }
}
```