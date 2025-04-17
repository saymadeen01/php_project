-- ================================
-- DROP existing tables (safe reset)
-- ================================
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS packages;
DROP TABLE IF EXISTS transport;
DROP TABLE IF EXISTS grades;

-- ================================
-- Users Table (Authentication)
-- ================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================
-- Grades Table
-- ================================
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(100) NOT NULL,
    grade VARCHAR(2) NOT NULL,
    subject VARCHAR(100),
    date DATE
);

-- ================================
-- Packages Table
-- ================================
CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_name VARCHAR(100) NOT NULL,
    weight DECIMAL(10,2) NOT NULL,
    type VARCHAR(50),
    grade_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE SET NULL
);

-- ================================
-- Transport Table
-- ================================
CREATE TABLE transport (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle VARCHAR(100) NOT NULL,
    driver VARCHAR(100) NOT NULL,
    origin VARCHAR(150),
    destination VARCHAR(150),
    status ENUM('Pending', 'In Transit', 'Delivered') DEFAULT 'Pending',
    date DATE,
    latitude DECIMAL(10, 6),
    longitude DECIMAL(10, 6),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================
-- Insert sample data (Optional)
-- ================================

-- Sample admin user (password = 123456 hashed)
INSERT INTO users (name, email, password)
VALUES ('Admin User', 'admin@example.com', '$2y$10$WzOowQ2M7KkR5nIYxRfBBuJ7WW2/sRDoZyDvVEv3hW9gCUBDiXYtu');

-- Sample grades
INSERT INTO grades (crop_name, grade, subject, date)
VALUES 
('Wheat', 'A', 'Top quality wheat batch', '2025-04-10'),
('Rice', 'B', 'Mid-grade rice shipment', '2025-04-09');

-- Sample packages
INSERT INTO packages (package_name, weight, type, grade_id)
VALUES 
('Package One', 10.5, 'Box', 1),
('Package Two', 5.25, 'Bag', 2);

-- Sample transport
INSERT INTO transport (vehicle, driver, origin, destination, status, date, latitude, longitude)
VALUES 
('Truck 101', 'John Doe', 'Dhaka', 'Chittagong', 'In Transit', CURDATE(), 23.8103, 90.4125),
('Van 202', 'Jane Smith', 'Sylhet', 'Rajshahi', 'Pending', CURDATE(), 24.8949, 91.8687);
