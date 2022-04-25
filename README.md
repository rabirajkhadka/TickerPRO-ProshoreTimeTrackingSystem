## About 

 Source code for TickerPro - Proshore Time Tracking System

## API Docs


https://documenter.getpostman.com/view/16210167/UVypzHaY

## Technologies Used
- Laravel 9
- MySQL DB

## Installation
```
git clone https://github.com/rabirajkhadka/TickerPRO-ProshoreTimeTrackingSystem-Backend.git

cd TickerPRO-ProshoreTimeTrackingSystem-Backend

composer install

php artisan serve
```

## Features

### User
- User can register 
- User can login and logout
- User can view their details
- User can update their password
- User can generate password reset request

### Admin
- Admin can assign multiples roles to a user 
- Admin can view all the users
- Admin can delete user
- Admin routes are protected and can't be accessed by the user
- Admin can invite others with the specified role

