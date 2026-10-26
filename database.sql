-- TrendyKart (Fashion & Clothing) - Database
CREATE DATABASE IF NOT EXISTS trendykart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE trendykart;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  mobile VARCHAR(15) NOT NULL,
  password VARCHAR(255) NOT NULL,
  address TEXT,
  city VARCHAR(80), state VARCHAR(80), pincode VARCHAR(10),
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  image VARCHAR(255),
  description VARCHAR(255),
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  image VARCHAR(255),
  price DECIMAL(10,2) NOT NULL,
  discount DECIMAL(5,2) NOT NULL DEFAULT 0,      -- percentage
  short_desc VARCHAR(255),
  description TEXT,
  specifications TEXT,                            -- one "Key: Value" per line
  stock INT NOT NULL DEFAULT 0,
  rating DECIMAL(2,1) NOT NULL DEFAULT 4.0,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  customer_name VARCHAR(100), email VARCHAR(150), mobile VARCHAR(15),
  billing_address TEXT, shipping_address TEXT,
  city VARCHAR(80), state VARCHAR(80), pincode VARCHAR(10),
  total DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(30) NOT NULL,
  payment_status ENUM('Pending','Paid','Failed') DEFAULT 'Pending',
  order_status ENUM('Pending','Confirmed','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT,
  product_name VARCHAR(150),
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100), email VARCHAR(150), subject VARCHAR(150), message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin login: admin / Admin@123
INSERT INTO admins (username,password) VALUES ('admin','$2y$10$cZr2tX8Km.6/zL1jZjhpgeRj4cRj6Bpxm/MTHo4biN9ZmEkHn5JYe');
-- Demo customer: demo@example.com / User@123
INSERT INTO users (name,email,mobile,password,address,city,state,pincode)
VALUES ('Demo User','demo@example.com','9876543210','$2y$10$LHZQpWrGSe.BYF8kMHbbV.BEbbCDSNaTFYgaRBnlb71yfqmLc4m56','12 MG Road','Ahmedabad','Gujarat','380001');

INSERT INTO categories (name,description) VALUES
('Men','Shirts, tees, jeans and jackets for men'),
('Women','Kurtis, dresses, tops and ethnic wear'),
('Kids','Comfortable and colourful kidswear'),
('Footwear','Sneakers, sandals and formal shoes'),
('Accessories','Watches, bags, belts and sunglasses');

INSERT INTO products (category_id,name,price,discount,short_desc,description,specifications,stock,rating,is_featured) VALUES
(1,'Classic Cotton Shirt',1299,20,'Breathable slim-fit shirt','Premium cotton shirt ideal for office and casual wear.','Fabric: 100% Cotton\nFit: Slim\nWash: Machine wash',50,4.5,1),
(1,'Denim Jacket',2999,15,'Rugged blue denim jacket','Timeless denim jacket with a comfortable lining.','Fabric: Denim\nFit: Regular\nColor: Blue',25,4.3,1),
(1,'Graphic Printed T-Shirt',699,10,'Soft round-neck tee','Everyday graphic tee in bio-washed cotton.','Fabric: Cotton\nNeck: Round',100,4.1,0),
(1,'Slim Fit Jeans',1799,25,'Stretch denim jeans','Comfort stretch jeans with a modern slim fit.','Fabric: Stretch Denim\nFit: Slim',60,4.4,0),
(2,'Floral Summer Dress',1999,30,'Light flowy midi dress','Breezy floral dress perfect for summer outings.','Fabric: Rayon\nLength: Midi',40,4.6,1),
(2,'Cotton Anarkali Kurti',1499,20,'Printed Anarkali kurti','Elegant kurti for festive and daily wear.','Fabric: Cotton\nSleeve: 3/4',35,4.4,1),
(2,'High-Waist Palazzo',899,0,'Comfortable palazzo pants','Wide-leg palazzo with elastic waist.','Fabric: Viscose',70,4.0,0),
(3,'Kids Cartoon Hoodie',999,15,'Warm fleece hoodie','Cosy hoodie with fun cartoon print.','Fabric: Fleece\nAge: 4-8 yrs',45,4.5,1),
(3,'Kids Denim Shorts',599,0,'Soft denim shorts','Durable shorts for active kids.','Fabric: Denim\nAge: 3-10 yrs',55,4.2,0),
(4,'Urban Running Sneakers',2499,20,'Lightweight sneakers','Cushioned sole sneakers for daily running.','Sole: EVA\nUpper: Mesh',30,4.6,1),
(4,'Leather Formal Shoes',3499,10,'Classic formal oxfords','Genuine leather formal shoes.','Material: Leather\nColor: Brown',20,4.3,0),
(5,'Analog Wrist Watch',1999,35,'Minimal analog watch','Stainless steel case with leather strap.','Strap: Leather\nWater resistant: 30m',15,4.4,1),
(5,'Canvas Backpack',1299,0,'Spacious 25L backpack','Durable canvas backpack with laptop sleeve.','Capacity: 25L',40,4.2,0);
