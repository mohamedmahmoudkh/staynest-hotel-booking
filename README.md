# StayNest - Hotel Booking Website

StayNest is a full-stack hotel booking website that allows users to search for hotels, view hotel details and available rooms, make bookings, and manage their reservations.

The project also includes an Admin Dashboard for managing hotels, rooms, and bookings.

## Project Overview

The website has two main types of users:

### User

Users can:

* Create an account
* Login securely
* Search for hotels
* View hotel details
* View available rooms
* Select a room
* Make a booking
* View their bookings
* Cancel a booking

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

## Tech Stack

### Frontend

* HTML5
* CSS3
* JavaScript

### Backend

* Node.js
* Express.js

### Database

* MySQL
* XAMPP
* phpMyAdmin

### Authentication

* JWT
* bcrypt

### Development Tools

* Git
* GitHub
* VS Code
* Postman

## Project Structure

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
│   ├── server.js
│   ├── package.json
│   ├── .env
│   │
│   ├── config/
│   │   └── db.js
│   │
│   ├── models/
│   │   ├── userModel.js
│   │   ├── hotelModel.js
│   │   ├── roomModel.js
│   │   └── bookingModel.js
│   │
│   ├── controllers/
│   │   ├── authController.js
│   │   ├── hotelController.js
│   │   ├── roomController.js
│   │   └── bookingController.js
│   │
│   ├── routes/
│   │   ├── authRoutes.js
│   │   ├── hotelRoutes.js
│   │   ├── roomRoutes.js
│   │   └── bookingRoutes.js
│   │
│   └── middleware/
│       ├── authMiddleware.js
│       └── adminMiddleware.js
│
├── database/
│   └── staynest.sql
│
├── .gitignore
└── README.md
```

## Database Structure

The main database tables are:

```text
users
hotels
rooms
bookings
```

### Users

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

### Hotels

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

### Rooms

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

### Bookings

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

### Relationships

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

## Team Tasks

### Task 1 - Frontend Home & Search

Responsible for the main user interface and hotel search.

* Home Page
* Navbar
* Footer
* Search Hotels
* Search Results
* Hotel Cards
* Filters
* Responsive Design

### Task 2 - Frontend Hotel Details & Booking

Responsible for the hotel details and booking interface.

* Hotel Details Page
* Hotel Information
* Hotel Images
* Rooms Display
* Room Selection
* Booking Form
* Booking Confirmation
* My Bookings UI

### Task 3 - Backend Authentication & Users

Responsible for authentication and user management.

* Node.js + Express setup
* MySQL connection
* Users table
* Register API
* Login API
* JWT Authentication
* Password Hashing
* Authentication Middleware
* User Profile API

### Task 4 - Backend Hotels & Rooms

Responsible for hotels and rooms management.

* Hotels table
* Rooms table
* Hotel APIs
* Add Hotel
* Edit Hotel
* Delete Hotel
* Add Room
* Edit Room
* Delete Room
* Get Hotels
* Get Hotel Details
* Get Available Rooms

### Task 5 - Booking System & Admin Dashboard

Responsible for the booking system and admin functionality.

* Bookings table
* Create Booking
* Check Room Availability
* My Bookings API
* Cancel Booking
* Admin Dashboard
* Manage Hotels
* Manage Rooms
* View Bookings
* Manage Bookings

## API Structure

The backend APIs will follow this structure:

```text
/api/auth
    POST /register
    POST /login

/api/users
    GET /profile

/api/hotels
    GET /
    GET /:id
    POST /
    PUT /:id
    DELETE /:id

/api/rooms
    GET /hotel/:hotelId
    POST /
    PUT /:id
    DELETE /:id

/api/bookings
    POST /
    GET /my-bookings
    DELETE /:id

/api/admin
    GET /bookings
```

The exact API structure can be adjusted during development if the team agrees on a better approach.

## How We Will Work

We will divide the project into separate tasks, but all tasks must follow the same database structure and API structure.

The development process will be:

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
7. Booking System
       ↓
8. Admin Dashboard
       ↓
9. Testing
       ↓
10. Final Integration
```

The frontend and backend can be developed at the same time.

For example:

```text
Frontend Developer
       ↓
Creates Hotel Details UI
       ↓
Uses expected API
       ↓
GET /api/hotels/:id

Backend Developer
       ↓
Creates Hotel API
       ↓
Returns hotel data
       ↓
Frontend consumes the API
```

## Git & GitHub Workflow

Everyone should work on their own branch.

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

### Workflow

Before starting work:

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

The team will review the changes before merging them into `main`.

## Important Team Rules

1. Do not work directly on `main`.
2. Pull the latest changes before starting new work.
3. Do not change another member's task without discussing it first.
4. Follow the agreed database structure.
5. Follow the agreed API naming.
6. Keep variable and file names clear and consistent.
7. Test your code before creating a Pull Request.
8. Do not commit `.env` files or passwords.
9. If you change an API, inform the team.
10. Communicate before making major structural changes.

## Environment Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd staynest-hotel-booking
```

### 2. Start XAMPP

Open XAMPP and start:

```text
Apache
MySQL
```

Then open phpMyAdmin and create the database:

```text
staynest
```

Import:

```text
database/staynest.sql
```

### 3. Install Backend Dependencies

```bash
cd backend
npm install
```

### 4. Configure Environment Variables

Create a `.env` file inside the backend folder:

```env
PORT=5000
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=staynest
JWT_SECRET=your_secret_key
```

Do not upload `.env` to GitHub.

### 5. Run the Backend

```bash
npm start
```

The backend should run on:

```text
http://localhost:5000
```

## Testing

Before submitting a Pull Request, test:

* Register
* Login
* JWT authentication
* Hotel search
* Hotel details
* Room availability
* Create booking
* View bookings
* Cancel booking
* Admin login
* Hotel CRUD
* Room CRUD
* Booking management

Use Postman to test backend APIs before connecting them to the frontend.

## Final Goal

The final system should provide a complete hotel booking experience:

```text
User
 ↓
Register / Login
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

And for Admin:

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

The main goal is to build a clean, functional, and easy-to-understand Full-Stack project where all five team members contribute and the final parts work together as one complete system.
