# StayNest - Hotel Booking Website

StayNest is a full-stack hotel booking website that allows users to search for hotels, view hotel details and available rooms, make bookings, and manage their reservations.

The project also includes an Admin Dashboard for managing hotels, rooms, and bookings.

---

## Project Overview

The website has two main types of users:

### User

Users can:

* Create an account
* Login and logout
* Search for hotels
* View hotel details
* View available rooms
* Select a room
* Make a booking
* View their bookings
* Cancel a booking
* View their profile information

### Admin

Admins can:

* Login to the admin dashboard
* Add hotels
* Edit hotels
* Delete hotels
* Add rooms
* Edit rooms
* Delete rooms
* View all bookings
* Manage bookings

---

## User Flow

```text
Home Page
    ↓
Search Hotels
    ↓
Search Results
    ↓
Hotel Details
    ↓
Available Rooms
    ↓
Choose Room
    ↓
Booking Form
    ↓
Booking Confirmation
    ↓
My Bookings
```

---

## Admin Flow

```text
Admin Login
    ↓
Admin Dashboard
    ↓
Manage Hotels
    ↓
Manage Rooms
    ↓
Manage Bookings
```

---

## Tech Stack

### Frontend

* HTML5
* CSS3
* JavaScript

### Backend

* PHP

### Database

* MySQL
* XAMPP
* phpMyAdmin

### Authentication

* PHP Sessions
* Password Hashing

### Development & Testing

* Git
* GitHub
* VS Code
* Postman

---

# Project Structure

```text
staynest-hotel-booking/
│
├── frontend/
│   │
│   ├── index.html
│   ├── login.html
│   ├── register.html
│   ├── hotels.html
│   ├── hotel-details.html
│   ├── booking.html
│   ├── my-bookings.html
│   │
│   ├── admin/
│   │   ├── dashboard.html
│   │   ├── hotels.html
│   │   ├── rooms.html
│   │   └── bookings.html
│   │
│   ├── css/
│   │   ├── style.css
│   │   ├── auth.css
│   │   ├── hotels.css
│   │   ├── booking.css
│   │   └── admin.css
│   │
│   └── js/
│       ├── main.js
│       ├── auth.js
│       ├── hotels.js
│       ├── booking.js
│       ├── my-bookings.js
│       └── admin.js
│
├── backend/
│   │
│   ├── config/
│   │   └── database.php
│   │
│   ├── auth/
│   │   ├── register.php
│   │   ├── login.php
│   │   └── logout.php
│   │
│   ├── users/
│   │   ├── profile.php
│   │   └── get-user.php
│   │
│   ├── hotels/
│   │   ├── get-hotels.php
│   │   ├── get-hotel.php
│   │   ├── add-hotel.php
│   │   ├── update-hotel.php
│   │   └── delete-hotel.php
│   │
│   ├── rooms/
│   │   ├── get-rooms.php
│   │   ├── add-room.php
│   │   ├── update-room.php
│   │   └── delete-room.php
│   │
│   ├── bookings/
│   │   ├── create-booking.php
│   │   ├── my-bookings.php
│   │   ├── cancel-booking.php
│   │   └── all-bookings.php
│   │
│   └── middleware/
│       └── auth.php
│
├── database/
│   └── staynest.sql
│
├── .gitignore
└── README.md
```

---

# Database Structure

The main database tables are:

```text
users
hotels
rooms
bookings
```

## Users Table

```text
users
----------------
id
name
email
password
phone
role
created_at
```

The `role` field can contain:

```text
user
admin
```

## Hotels Table

```text
hotels
----------------
id
name
location
description
image
rating
created_at
```

## Rooms Table

```text
rooms
----------------
id
hotel_id
room_type
price
capacity
description
image
available
```

## Bookings Table

```text
bookings
----------------
id
user_id
room_id
check_in
check_out
guests
total_price
status
created_at
```

---

# Database Relationships

```text
User
 │
 │ 1
 │
 │ Many
 ↓
Bookings
 │
 │ Many
 │
 │ 1
 ↓
Room
 │
 │ Many
 │
 │ 1
 ↓
Hotel
```

A user can have multiple bookings.

A room can have multiple bookings over different dates.

A hotel can have multiple rooms.

---

# Team Tasks

## Task 1 – Frontend Home & Search

Responsible for the main user interface and hotel search.

* Home Page
* Navbar
* Footer
* Search Hotels
* Search Results
* Hotel Cards
* Filters
* Responsive Design

---

## Task 2 – Frontend Hotel Details & Booking

Responsible for the hotel details and booking interface.

* Hotel Details Page
* Hotel Information
* Hotel Images
* Rooms Display
* Room Selection
* Booking Form
* Booking Confirmation
* My Bookings UI

---

## Task 3 – Backend Authentication & Users (PHP)

Responsible for authentication and user management using PHP.

* PHP Backend Setup
* XAMPP & MySQL Connection
* Users Table
* Register API
* Login API
* Password Hashing
* Session / Authentication Handling
* User Profile API
* Get User Information
* Basic API Testing using Postman

---

## Task 4 – Backend Hotels & Rooms (PHP)

Responsible for hotels and rooms management.

* Hotels Table
* Rooms Table
* MySQL Queries
* Get All Hotels
* Get Hotel Details
* Add Hotel
* Edit Hotel
* Delete Hotel
* Get Available Rooms
* Add Room
* Edit Room
* Delete Room
* Basic API Testing using Postman

---

## Task 5 – Booking System & Admin Dashboard (PHP)

Responsible for the booking system and admin functionality.

* Bookings Table
* Create Booking
* Check Room Availability
* My Bookings
* Cancel Booking
* Admin Dashboard
* Manage Hotels
* Manage Rooms
* View Bookings
* Manage Bookings
* Basic API Testing using Postman

---

# API Structure

The backend APIs will be organized into separate PHP endpoints.

## Authentication

```text
POST /auth/register.php
POST /auth/login.php
POST /auth/logout.php
```

## Users

```text
GET /users/profile.php
GET /users/get-user.php
```

## Hotels

```text
GET    /hotels/get-hotels.php
GET    /hotels/get-hotel.php
POST   /hotels/add-hotel.php
POST   /hotels/update-hotel.php
POST   /hotels/delete-hotel.php
```

## Rooms

```text
GET    /rooms/get-rooms.php
POST   /rooms/add-room.php
POST   /rooms/update-room.php
POST   /rooms/delete-room.php
```

## Bookings

```text
POST   /bookings/create-booking.php
GET    /bookings/my-bookings.php
POST   /bookings/cancel-booking.php
GET    /bookings/all-bookings.php
```

The exact endpoints can be adjusted if the team agrees on a better structure.

---

# Authentication Flow

Authentication will be handled using PHP Sessions.

```text
User
 ↓
Register
 ↓
Password Hashing
 ↓
Database
 ↓
Login
 ↓
Verify Email & Password
 ↓
Create PHP Session
 ↓
Authenticated User
```

Protected APIs will check whether the user has an active session before allowing access.

Example:

```text
Request
   ↓
Session Check
   ↓
Authenticated?
  / \
Yes  No
 ↓    ↓
Allow  Return Error
```

---

# How The Team Will Work

The project will be developed in separate tasks, but all team members must follow the same database structure, API structure, and naming conventions.

The development process:

```text
1. Project Setup
       ↓
2. Database Setup
       ↓
3. Backend APIs
       ↓
4. Frontend Pages
       ↓
5. Connect Frontend with Backend
       ↓
6. Authentication
       ↓
7. Hotel & Room System
       ↓
8. Booking System
       ↓
9. Admin Dashboard
       ↓
10. Testing
       ↓
11. Final Integration
```

Frontend and backend development can happen at the same time.

For example:

```text
Frontend
   ↓
Hotel Details Page
   ↓
Requests Hotel Data
   ↓
GET /hotels/get-hotel.php
   ↓
Backend
   ↓
MySQL
   ↓
Returns Hotel Data
   ↓
Frontend Displays Data
```

---

# Git & GitHub Workflow

Each team member should work on their own branch.

Do not directly push development work to `main`.

Example:

```text
main
 │
 ├── feature/frontend-home
 │
 ├── feature/frontend-booking
 │
 ├── feature/backend-auth
 │
 ├── feature/backend-hotels
 │
 └── feature/booking-admin
```

## Before Starting Work

```bash
git checkout main
git pull origin main
```

Create your branch:

```bash
git checkout -b feature/your-task
```

After finishing your work:

```bash
git add .
git commit -m "Add hotel search page"
git push origin feature/your-task
```

Then create a Pull Request on GitHub.

The changes should be reviewed before merging into `main`.

---

# Important Team Rules

1. Do not work directly on `main`.
2. Pull the latest changes before starting new work.
3. Do not modify another member's task without discussing it first.
4. Follow the agreed database structure.
5. Follow the agreed API structure.
6. Keep file and variable names clear and consistent.
7. Test your code before creating a Pull Request.
8. Do not upload passwords or sensitive configuration to GitHub.
9. Inform the team before changing an API.
10. Communicate before making major structural changes.
11. Keep commits clear and related to one feature.
12. Resolve merge conflicts with the team member responsible for the affected code.

---

# Local Environment Setup

## 1. Install XAMPP

Install XAMPP and start:

```text
Apache
MySQL
```

## 2. Project Location

Place the project inside the XAMPP `htdocs` folder:

```text
xampp/
└── htdocs/
    └── staynest-hotel-booking/
```

## 3. Create Database

Open phpMyAdmin and create:

```text
staynest
```

Import the SQL file:

```text
database/staynest.sql
```

## 4. Database Connection

Configure the database connection inside:

```text
backend/config/database.php
```

Example configuration:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "staynest";
```

Do not upload real database passwords or sensitive credentials to GitHub.

## 5. Run The Project

Start Apache and MySQL from XAMPP.

Then open the project through:

```text
http://localhost/staynest-hotel-booking/
```

---

# Testing

Postman will be used to test the PHP APIs before connecting them to the frontend.

Test the following:

### Authentication

* Register
* Login
* Logout
* Session Authentication
* Get User Information

### Hotels

* Get Hotels
* Get Hotel Details
* Add Hotel
* Update Hotel
* Delete Hotel

### Rooms

* Get Rooms
* Add Room
* Update Room
* Delete Room
* Check Availability

### Bookings

* Create Booking
* Get My Bookings
* Cancel Booking
* Get All Bookings

### Admin

* Admin Authentication
* Manage Hotels
* Manage Rooms
* Manage Bookings

---

# Final System

The final system should provide a complete hotel booking experience.

## User

```text
Register
   ↓
Login
   ↓
Search Hotels
   ↓
View Hotel
   ↓
View Rooms
   ↓
Select Room
   ↓
Book Room
   ↓
View Booking
   ↓
Cancel Booking
```

## Admin

```text
Admin Login
   ↓
Dashboard
   ↓
Manage Hotels
   ↓
Manage Rooms
   ↓
Manage Bookings
```

---

# Future Improvements

These features are not required for the initial version but can be added later:

* Online Payment
* Hotel Reviews & Ratings
* Advanced Search Filters
* Email Booking Confirmation
* Hotel Location Map
* Wishlist
* Discount & Coupon System
* Advanced Admin Statistics

---

# Project Goal

The goal of StayNest is to build a clean, functional, beginner-friendly Full-Stack Hotel Booking System.

Each team member will be responsible for a specific part of the project, and all parts will be integrated together to create one complete working website.
