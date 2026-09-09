CREATE DATABASE IF NOT EXISTS staynest;
USE staynest;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30),
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    rating DECIMAL(2,1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_number VARCHAR(30) NOT NULL,
    room_type VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    description TEXT,
    image VARCHAR(500),
    available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE RESTRICT
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('confirmed','cancelled','completed') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT
);

-- ============================================================
-- StayNest sample / demo data
-- Password for both demo accounts: password123
-- ============================================================

INSERT INTO users (name, email, password, phone, role) VALUES
('StayNest Admin', 'admin@staynest.com', '$2y$12$.SP.bkSqlwVuXDLDFOr59uR4/nMVN/Ta611kSM/XS4uBh7M5rlVhq', '01000000001', 'admin'),
('Demo User', 'user@staynest.com', '$2y$12$.SP.bkSqlwVuXDLDFOr59uR4/nMVN/Ta611kSM/XS4uBh7M5rlVhq', '01000000002', 'user');

INSERT INTO hotels (name, location, description, image, rating) VALUES
('Nile View Grand Hotel', 'Cairo, Egypt', 'A comfortable city hotel with beautiful Nile views, modern rooms, and easy access to central Cairo.', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80', 4.8),
('Pyramids Palace Hotel', 'Giza, Egypt', 'A relaxing hotel near the Giza Plateau, offering spacious rooms and convenient access to the pyramids.', 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80', 4.6),
('Alexandria Sea Breeze', 'Alexandria, Egypt', 'A seaside stay with bright rooms, comfortable facilities, and easy access to the Mediterranean coast.', 'https://images.unsplash.com/photo-1601918774946-25832a4be0d6?auto=format&fit=crop&w=1200&q=80', 4.5),
('Luxor Heritage Hotel', 'Luxor, Egypt', 'A peaceful hotel for travelers exploring Luxor temples, museums, and the West Bank.', 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1200&q=80', 4.7),
('Red Sea Resort', 'Hurghada, Egypt', 'A sunny resort-style hotel with comfortable rooms and quick access to Red Sea beaches.', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=1200&q=80', 4.9);

INSERT INTO rooms (hotel_id, room_number, room_type, price, capacity, description, image, available) VALUES
(1, '101', 'Standard Room', 85.00, 2, 'Comfortable room with a queen bed, private bathroom, Wi-Fi, and city views.', 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=900&q=80', 1),
(1, '201', 'Deluxe Nile View', 135.00, 2, 'Deluxe room with a king bed and a relaxing view toward the Nile.', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80', 1),
(1, '301', 'Family Suite', 190.00, 4, 'Large suite suitable for families, with two sleeping areas and a spacious sitting area.', 'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=900&q=80', 1),
(2, '102', 'Standard Room', 70.00, 2, 'Simple and comfortable room close to the Giza attractions.', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=900&q=80', 1),
(2, '202', 'Pyramid View Room', 120.00, 2, 'Bright room with a memorable view toward the pyramids.', 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=900&q=80', 1),
(3, '103', 'Sea View Room', 110.00, 2, 'Comfortable double room with a Mediterranean sea view.', 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=900&q=80', 1),
(3, '203', 'Family Room', 155.00, 4, 'Spacious family room with flexible sleeping arrangements.', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=900&q=80', 1),
(4, '105', 'Heritage Room', 95.00, 2, 'Quiet room with traditional-inspired details and modern facilities.', 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=900&q=80', 1),
(4, '205', 'Deluxe Suite', 160.00, 3, 'Spacious suite for couples or small families visiting Luxor.', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80', 1),
(5, '110', 'Garden Room', 100.00, 2, 'Comfortable room with a calm resort atmosphere.', 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=900&q=80', 1),
(5, '210', 'Sea View Suite', 180.00, 3, 'Premium suite with extra space and a beautiful Red Sea view.', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=900&q=80', 1);

-- Demo booking 1: confirmed and intentionally active for availability testing.
INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_price, status) VALUES
(2, 1, '2026-09-15', '2026-09-18', 2, 255.00, 'confirmed'),
(2, 6, '2026-10-05', '2026-10-08', 2, 330.00, 'cancelled');
