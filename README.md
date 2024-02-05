## Mailerlite Subscriber API
A simple API to manage subscribers

## Technical Requirements
- Apache Server v2.4+
- PHP v8+
- MySQL v5+

## Instruction running the API locally

- Clone the project on your webserver
- Setup the database by running the sql file found at the root of the project (user.sql)
- Populate the config.sample.php with your database credentials and rename it to config.php
- Test with your preffered API client e.g Postman


## Endpoint Definitions

### Base URL

This will depend on where you have deployed this API:

e.g http://localhost/mailerlite/index.php

### HTTP Methods

The following HTTP Methods are used in this API:

GET - Retrieve a resource
POST - Create a resource

### Endpoints 

#### /user/create
POST - http://localhost/mailerlite/index.php/user/create
##### Body Params
name - string
lastname - string
email - string

#### user/search?email=test@gmail.com
GET - http://localhost/mailerlite/index.php/user/search?email=test@gmail.com
##### Query Params
email - string


