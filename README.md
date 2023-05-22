# VentureTest
Venture test task

This is web application for challenge from Venture.

The main task was: 

>So here is our Chuck Norris Challenge that we would like you to code:
Please write an application in React for the frontend and PHP for the api that:

>* takes a variable amount of valid Email Addresses from the User
>* Sorts them by: 1st Domain name, 2nd name part of the email
>* Sends with a click of a button a Chuck Norris joke within a HTML email to the email
addresses

>Jokes can be received from this API: https://api.chucknorris.io
>Write the application as if you would roll it out to a potential customer. That means also think about how you secure your code quality and architecture. Keep the design efforts to a bare minimum.

In this repository you find dockerized web application, separated on 5 parts.

Before up of docker-compose you need to generate `.env` file. Here is template for it

```.env
# This is mysql settings
MYSQL_ROOT_USER=root
MYSQL_ROOT_PASSWORD=mysecretpassword
MYSQL_DATABASE=dev
MYSQL_USER=myuser
MYSQL_PASSWORD=mypassword

# This is SMTP settings for email sending
SMTP_HOST=somesmtphost.com
SMTP_PORT=465
SMTP_USER=someexpampleemail@gmail.com
SMTP_PASSWORD=somesmtppassword
SMTP_FROM=someexpampleemail@gmail.com
SMTP_NAME=TestUser
```

---
## Front

**Front** - is React application located on default address (`<host/>`)

Front is simple one page application separated on header, footer and body. In body there are only 2 parts - input of emails and send button.

Email input can take on itself part of EXcel table to read bunch of emails. Email input block adjust itself automatically. In case some of emails are not validated there is error output under each input.

## Back

**Back** - is PHP service working with database. Back located on address `<host/backend>`. Back implements two API functions.

*GET* /backend/api/emails/read.php - this function return all emails sorted firstly by domain name, and secondly by body

*POST* /backend/api/emails/create.php - this function create new input for database, and also send email to new user. The body of this request nee to be raw json with this structure:

```json
{
    "email": "sometest@mail.com",
}
```

## Database

**Database** - is mysql database with one simple table. Settings of table you can find by path /database/scripts/init.sql

```sql
CREATE DATABASE IF NOT EXISTS dev;
USE dev;
CREATE TABLE IF NOT EXISTS emails (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    full_email VARCHAR(250),
    email_domain VARCHAR(150),
    email_body VARCHAR(150),
    PRIMARY KEY (id)
);
```

How you can see it is simple SQL database with only 4 columns. `email_domain` and `email_body` - used only for sorting.

## Mail

This part contains source files for e-mail template generation. I used MJML for it to prevent some misreadings in several e-mail applications. If you need to regenerate email you need to use command

```bash
cd ./mail
npm install
./node_modules/.bin/mjml -r index.mjml -o ./build/index.hrml
```

It is important to build particularly in `/mail/build/` folder, cause **Back** will get html template for email from there.

## NGINX

It is default nginx configure file that let front work from default address and back from `/backend/` address.