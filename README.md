# liloapp
URL : http://lilo.blitzworx.com/api/

X-Api-Key `AQLlSvDbvCAI9a!uduCy_FgCNcWNsV8oiEUe`

## Table of contents

1. **Visitors**
    + [Guest Login](#guest-login)
    + [Cesbie Login](#cesbie-login)

1. **Guest Login Steps**
    + [Get Attached Agency](#get-attached-agency)
    + [Get Attached Agency OTHERS](#get-attached-agency-others)
    + [Get Division](#get-division)
    + [Get Services by division](#get-services-by-division)
    + [Step 1](#step-1)
    + [Step 2](#step-2)
    + [Get Regions - Step 3](#step-3)
    + [Get Provinces](#get-provinces)
    + [Get Cities](#get-cities)
    + [Get Provinces and Cities](#get-provinces-and-cities)

1. **Cesbie Login Steps**
    + [Step 1](#step-1)

1. **Guest Logout Steps**
    + [Step 1](#step-1)
    + [Step 2](#step-2)

1. **Cesbie Logout**
    + [Cesbie Logout](#cesbie-logout)

## Visitors

### Guest Login
POST `api/visitors/guest-login/`   

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| fullname        |  yes     |  varchar      |        -              |  Elline Ocampo
| agency        |       |  varchar      |        id of from tbl.agency              |  1
| attached_agency        |       |  varchar      |        id of from tbl.agency              |   1
| **attached_agency_others**        | optional |  varchar      |        Any string              |   Test Other Agency
| email_address        |  yes     |  varchar      |        -              |  edocampo@myoptimind.com
| is_have_ecopy        |       |  varchar      |        0 = none, 1 = yes              |  1
| photo       |  yes     |  file |      |  testing.pmg
| division_to_visit        |  only if services     |  varchar      |        id of from tbl.division              |  2
| purpose[]        |  yes only if services    |  array      |id of from tbl.services  | 1  
| person_to_visit[]        |  yes only if person   |  array      |        id of from tbl.staffs             |  1
| temperature        |  yes     |  varchar      |        -              |  37.3
| home_address        |  yes     |  text      |        -              |  My home
| region        |  yes     |  varchar      |        -              |  Rizal
| province        |  no     |  varchar      |        -              |  
| city        |  yes     |  varchar      |        -              |  Hello city
| mobile_number       |  yes     |  varchar |      |  09497912581
| is_recent_contact        |  yes     |  bool      |                      |  1
| recent_contact_details        | optional |  text      |                      |  ha
| is_travelled_locally        |  yes     |  bool      |                      |  1
| travelled_locally_details |  optional     |  text      |                      |  how you like that
| health_condition        |  yes     |  varchar      |                      |  Normal


##### Response
```javascript
201 OK
{
  "data": {
    "id": "298",
    "fullname": "enzo",
    "agency": "Department of Agrarian Reform Adjudication Board",
    "attached_agency": "Department of Agrarian Reform Adjudication Board",
    "email_address": "l@l.com",
    "is_have_ecopy": "0",
    "photo": "298-1600069404_zey7w0okowf51.png",
    "division_to_visit": null,
    "purpose": "",
    "person_to_visit": "Lorenzo Salamante, Diane Ocampo",
    "temperature": "33.3°C",
    "place_of_origin": "",
    "region": "National Capital Region (NCR)",
    "city": "Marikina City",
    "province": "",
    "is_recent_contact": "0",
    "recent_contact_details": "",
    "is_travelled_locally": "1",
    "travelled_locally_details": "travelled recently hehe",
    "mobile_number": "0999999999",
    "health_condition": "Normal",
    "pin_code": "SA9EB7",
    "created_at": "2020-09-14 15:43:23",
    "updated_at": null,
    "deleted_at": "0000-00-00 00:00:00",
    "login_time_format": "9\/14\/2020 | 3:43 PM"
  },
  "meta": {
    "message": "Guest Visitor login successfully",
    "status": "201"
  }
}
```

### Cesbie Login
POST `api/visitors/cesbie-login/`   

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| staff_id        |  yes     |  int      |        id of from tbl.staff              |  1
| temperature        |  yes     |  varchar      |        -              |  37.3
| health_condition        |  yes     |  varchar      |        -              |  Normal
| location_prior        |  yes     |  varchar      |        -              |  Others
| location_prior_others    |  yes     |  varchar      |        -              |  Mall
| has_contact    |  yes     |  boolean      |        -              |  1
| has_contact_others   |  yes     |  varchar      |        -              |  Description
| has_travelled    |  yes     |  boolean      |        -              |  1
| has_travelled_others   |  yes     |  varchar      |        -              |  Description

##### Response
```javascript
201 OK
{
    "data": {
        "id": "18",
        "staff_id": "1",
        "temperature": "37.0",
        "place_of_origin": "Quezon City",
        "pin_code": "AN412500018C",
        "created_at": "2020-07-29 15:41:25",
        "updated_at": "2020-07-29 15:41:25",
        "deleted_at": "0000-00-00 00:00:00",
        "login_time_format": "7/29/2020 | 3:41 PM",
        "staff": {
            "id": "1",
            "fullname": "Lorenzo Salamante",
            "email_address": "enzo@enzo.com",
            "division_id": "1",
            "is_active": "1",
            "created_at": "2020-07-21 11:55:58",
            "updated_at": "2020-07-24 12:12:43",
            "deleted_at": "0000-00-00 00:00:00"
        }
    },
    "meta": {
        "message": "Cesbie Visitor login successfully",
        "status": "201"
    }
}
```

## Guest Login Steps

### Get attached agency
GET `api/visitors/attached-agency/{agency_id}`   

Get list of attached agency options under agency

##### Response
```javascript
200 OK
{
    "data": [
        {
            "id": "48",
            "name": "Amang Rodriguez Medical Center"
        },
        {
            "id": "49",
            "name": "Bureau of Health Devices and Technology"
        }
        ...
    ],
    "meta": {
        "message": "Data found",
        "status": "200"
    }
}
```

### Get attached agency OTHERS
GET `api/visitors/attached_agency_others`   

##### Response
```javascript
200 OK
{
  "data": [
    {
      "name": "Test Attached Agency"
    },
    {
      "name": "Test Attached Agency 2"
    },
    ...
  ],
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```
### Get Division
GET `api/visitors/division`

Get list of options for divisions

##### Response
```javascript
200 OK
{
  "data": [
    {
      "id": "2",
      "name": "Eligibility and Rank Appointment"
    },
    {
      "id": "6",
      "name": "Finance and Administrative Division"
    },
    {
      "id": "1",
      "name": "Office of the Executive Director"
    },
    {
      "id": "4",
      "name": "Performance Management and Assistance Division"
    },
    {
      "id": "5",
      "name": "Policy, Planning and Legal Division"
    },
    {
      "id": "3",
      "name": "Professional Development Division"
    }
  ],
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```

### Get services by division
GET `api/visitors/services/:division_id`

Get list of options for services by division

##### Response
```javascript
200 OK
{
  "data": [
    {
      "id": "35",
      "name": "Human Resource Management and Development"
    },
    {
      "id": "36",
      "name": "Financial Management"
    },
    {
      "id": "37",
      "name": "Supplies and Property Management"
    },
    {
      "id": "38",
      "name": "Information and Records Management"
    },
    {
      "id": "39",
      "name": "General Services, Building and Grounds"
    },
    {
      "id": "40",
      "name": "Job Order Services"
    },
    {
      "id": "41",
      "name": "Administrative Concerns"
    }
  ],
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```

### Step 1
GET `api/visitors/guest-login/step-1`   

Get list of options for agency

##### Response
```javascript
200 OK
{
    "data": {
        "agency": [
            {
                "id": "1",
                "name": "DTI"
            },
            {
                "id": "2",
                "name": "DOST"
            }
        ],
        "attached_agency": []
    },
    "meta": {
        "message": "Data found",
        "status": "200"
    }
}
```

### Step 2
GET `api/visitors/guest-login/step-2`   

Get list of options for division, purpose, person to visit.

##### Response
```javascript
200 OK
{
    "data": {
        "division": [
            {
                "id": "2",
                "name": "DFA"
            },
            {
                "id": "3",
                "name": "DepEd"
            }
        ],
        "purpose": [
            {
                "id": "1",
                "name": "Meeting"
            },
            {
                "id": "2",
                "name": "Visit"
            }
        ],
        "person_to_visit": [
            {
                "id": "1",
                "fullname": "Lorenzo Salamante"
            },
            {
                "id": "2",
                "fullname": "Diane Ocampo"
            }
        ]
    },
    "meta": {
        "message": "Data found",
        "status": "200"
    }
}
```

### Step 3
GET `api/visitors/guest-login/step-3`   

Get list of options for place of origin.

##### Response
```javascript
200 OK
{
  "data": {
    "place_of_origin": [
      {
        "name": "National Capital Region (NCR)"
      },
      {
        "name": "Cordillera Administrative Region (CAR)"
      },
      {
        "name": "Ilocos (Region I)"
      },
      {
        "name": "Cagayan Valley (Region II)"
      },
      {
        "name": "Central Luzon (Region III)"
      },
      {
        "name": "CALABARZON (Region IV-A)"
      },
      {
        "name": "MIMAROPA (Region IV-B)"
      },
      {
        "name": "Bicol (Region V)"
      },
      {
        "name": "Western Visayas (Region VI)"
      },
      {
        "name": "Central Visayas (Region VII)"
      },
      {
        "name": "Eastern Visayas (Region VIII)"
      },
      {
        "name": "Zamboanga Peninsula (Region IX)"
      },
      {
        "name": "Northern Mindanao (Region X)"
      },
      {
        "name": "Davao (Region XI)"
      },
      {
        "name": "SOCCSKSARGEN (Region XII)"
      },
      {
        "name": "Caraga (Region XIII)"
      },
      {
        "name": "Bangsamoro (BARMM)"
      }
    ]
  },
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```

### Get Provinces
POST `api/get-provinces`   

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| region        |  yes     |  text      |        -              |  Bangsamoro (BARMM)

Get list of cities and provinces options under a region.

##### Response
```javascript
200 OK
{
  "data": {
    "provinces": [
      {
        "name": "Agoncillo"
      },
      {
        "name": "Alitagtag"
      },
      {
        "name": "Balayan"
      },
      {
        "name": "Balete"
      },
      {
        "name": "Bauan"
      },
      {
        "name": "Calaca"
      },
      {
        "name": "Calatagan"
      },
      ...
    ]
  },
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```

### Get Cities
POST `api/get-cities`   

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| province        |  yes     |  text      |        -              |  Albay

Get list of cities and provinces options under a region.

##### Response
```javascript
200 OK
{
  "data": {
    "cities": [
      {
        "name": "Bacacay"
      },
      {
        "name": "Camalig"
      },
      {
        "name": "Daraga"
      },
      {
        "name": "Guinobatan"
      },
      {
        "name": "Jovellar"
      },
     ...
    ]
  },
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```

### Get Provinces and Cities
POST `api/get-provinces-and-cities`   

Get list of cities and provinces options under a region.
|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| region        |  yes     |  text      |        -              |  Bangsamoro (BARMM)


##### Response
```javascript
200 OK
{
  "data": {
    "provinces_and_cities": [
      {
        "city_id": "14",
        "province_id": "1",
        "province_and_city": "Metro Manila, Quezon",
        "province_name": "Metro Manila",
        "cities_name": "Quezon"
      },
      {
        "city_id": "11",
        "province_id": "1",
        "province_and_city": "Metro Manila, Pasay",
        "province_name": "Metro Manila",
        "cities_name": "Pasay"
      },
      {
        "city_id": "8",
        "province_id": "1",
        "province_and_city": "Metro Manila, Muntinlupa",
        "province_name": "Metro Manila",
        "cities_name": "Muntinlupa"
      },
     ...
    ]
  },
  "meta": {
    "message": "Data found",
    "status": "200"
  }
}
```

## Cesbie Login Steps

### Step 1
GET `api/visitors/cesbie-login/`   

Get list of options for cesbie fullname, and place of origin.

##### Response
```javascript
200 OK
{
    "data": {
        "staff": [
            {
                "id": "3",
                "fullname": "Abigail Emerson"
            },
            {
                "id": "12",
                "fullname": "Charissa Harrington"
            },
            {
                "id": "2",
                "fullname": "Diane Ocampo"
            },
            {
                "id": "8",
                "fullname": "Dustin Carpenter"
            },
            {
                "id": "13",
                "fullname": "Isabelle Hays"
            },
            {
                "id": "14",
                "fullname": "Kylan Powell"
            },
            {
                "id": "6",
                "fullname": "Lareina Dean"
            },
            {
                "id": "17",
                "fullname": "Leo Conley"
            },
            {
                "id": "1",
                "fullname": "Lorenzo Salamante"
            },
            {
                "id": "4",
                "fullname": "Prescott Kelly"
            },
            {
                "id": "16",
                "fullname": "Rinah Dalton"
            },
            {
                "id": "10",
                "fullname": "Tiger Gaines"
            },
            {
                "id": "5",
                "fullname": "Vaughan Nash"
            },
            {
                "id": "7",
                "fullname": "Vivien Cervantes"
            },
            {
                "id": "15",
                "fullname": "Wing Kent"
            },
            {
                "id": "11",
                "fullname": "Yen Kline"
            }
        ],
        "place_of_origin" : [
            {
                "name": "Aborlan, Palawan"
            },
            {
                "name": "Abra de Ilog, Occidental Mindoro"
            },
            {
                "name": "Abucay, Bataan"
            },
            {
                "name": "Abulug, Cagayan"
            },
            {
                "name": "Abuyog, Leyte"
            },
            {
                "name": "Adams, Ilocos Norte"
            },
            {
                "name": "Agdangan, Quezon"
            },
            {
                "name": "Aglipay, Quirino"
            },
            {
                "name": "Agno, Pangasinan"
            },
            {
                "name": "Agoncillo, Batangas"
            },
            {
                "name": "Agoo, La Union"
            },
            ...
            ...
            ...
            ...
        ]
    },
    "meta": {
        "message": "Data found",
        "status": "200"
    }
}
```

## Guest Logout Steps

### Step 1
POST `api/visitors/logout/step-1`   

Check the pin_code if valid.

Returns in the data response the visitor type.

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| pin_code        |  yes     |  varchar      |        pin_code either from tbl.guest_visitors or tbl.cesbie_visitors              |  zXc12365gfd123asd


##### Response
```javascript
200 OK
{
    "data": "guest_visitors",
    "meta": {
        "message": "Valid pin code",
        "status": "200"
    }
}
```

### Step 2
POST `api/visitors/logout/step-2`   

Check the pin_code if valid.

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| pin_code        |  yes     |  varchar      |        pin_code either from tbl.guest_visitors or tbl.cesbie_visitors              |  zXc12365gfd123asd
| overall_experience        |  yes     |  int      |        1 = bad, 2 = okay, 3 = good              |  1
| feedback        |       |  varchar      |       feedback message of visitor              |  Lorem ipsum dolor sit amet, consecteturdipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodoconsequat. Duis aute irure dolor in reprehenderit in voluptate velit essecillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat nonproident, sunt in culpa qui officia deserunt mollit anim id est laborum.


##### Response
```javascript
201 OK
{
  "data": {
    "login_time_format": "9\/14\/2020 | 4:28 PM",
    "logout_time_format": "9\/14\/2020 | 4:28 PM",
    "fullname": "enzo",
    "agency": "Department of Agrarian Reform Adjudication Board",
    "attached_agency": "Department of Agrarian Reform Adjudication Board",
    "email_address": "l@l.com",
    "division": null,
    "is_have_ecopy": "0",
    "person_visited": "1,2",
    "purpose": "",
    "temperature": "33.3°C",
    "place_of_origin": "National Capital Region (NCR), Test, Marikina City",
    "region": "National Capital Region (NCR)",
    "province": "Test",
    "city": "Marikina City",
    "login_time": "2020-09-14 16:28:23",
    "logout_time": "2020-09-14 16:28:36",
    "pin_code": "DP5D4B",
    "person_to_visit": "Lorenzo Salamante, Diane Ocampo",
    "duration": "13 secs"
  },
  "meta": {
    "post": {
      "pin_code": "DP5D4B",
      "overall_experience": "1",
      "feedback": ""
    },
    "ecopy": "",
    "message": "Logout successfully",
    "status": "200"
  }
}
```

## Cesbie Logout

### Cesbie Logout
POST `api/visitors/cesbie-logout/`   

##### Payload

|      Name      | Required |   Type    |    Description        |    Sample Data
|----------------|----------|-----------|-----------------------|-----------------------
| staff_id        |  yes     |  varchar      |        id from tbl.staffs              |  1


##### Response
```javascript
200 OK
{
    "data": {
        "fullname": "Lorenzo Salamante",
        "division": "Dost",
        "temperature": "37.0",
        "place_of_origin": "Quezon City",
        "login_time_format": "8/04/2020 | 12:06 PM",
        "logout_time_format": "8/04/2020 | 12:07 PM",
        "login_time": "2020-08-04 12:06:17",
        "logout_time": "2020-08-04 12:07:34",
        "duration": "1 min"
    },
    "meta": {
        "message": "Cesbie logout successfully",
        "status": "200"
    }
}
```
##### Response
```javascript
404 Data not found
{
    "data": [],
    "meta": {
        "message": "Data not found",
        "status": "404"
    }
}
```
