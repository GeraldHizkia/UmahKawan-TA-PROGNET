# UmahKawan - Authentic Indonesian Fruit Salad 🥗

**UmahKawan** is a web-based ordering system for a local culinary gem in Bali, specializing in *Rujak* (Indonesian fruit salad), *Tipat Cantok*, and other authentic Balinese delicacies. The name "Umah Kawan" embodies a welcoming atmosphere where friends and family gather to enjoy fresh, spicy, and tangy flavors.

![UmahKawan Banner](img/warung%20rujak.png)

## 📖 About The Project

This project simulates a full-featured e-commerce website for a local food business. It allows customers to browse the menu, add items to a cart, and place orders directly. It also features WhatsApp integration for quick communication and a robust backend for order processing.

**Location**: Jl. P. Bungin I No.14, Pedungan, Denpasar Selatan, Kota Denpasar, Bali.

## ✨ Features

*   **Responsive Design**: Built with Bootstrap for a seamless experience on mobile and desktop.
*   **Menu Showcase**: Categorized menu items (Rujak, Tipat Cantok, Plecing, Drinks) with images and prices.
*   **Shopping Cart**: Add items, adjust quantities, and calculate subtotals dynamically.
*   **Checkout System**:
    *   Input customer details (Name, Phone, Address).
    *   Select payment methods (Cash, Transfer, QRIS).
    *   Order storage in MySQL database.
*   **WhatsApp Integration**: Direct "Order via WhatsApp" button for quick ordering.
*   **Map Integration**: Google Maps embed for easy location finding.

## 🛠 Tech Stack

*   **Backend**: Native PHP (No framework)
*   **Frontend**: HTML5, CSS3, JavaScript (jQuery)
*   **Styling**: Bootstrap 4, Owl Carousel (for sliders), Animate.css
*   **Database**: MySQL
*   **Server**: Apache (via XAMPP/Laragon)

## 🚀 Installation & Setup

1.  **Prerequisites**:
    *   Install a local server environment like [XAMPP](https://www.apachefriends.org/) or [Laragon](https://laragon.org/).
    *   Ensure PHP and MySQL are running.

2.  **Clone the Repository**:
    ```bash
    git clone https://github.com/GeraldHizkia/UmahKawan-TA-PROGNET.git
    ```
    *(Or download the ZIP and extract it to your server's root directory, e.g., `htdocs` or `www`)*

3.  **Database Setup**:
    *   Open your database manager (navicat/phpMyAdmin/HeidiSQL).
    *   Create a new database named **`umahkawan`**.
    *   Run the following SQL commands to create the necessary tables:

    ```sql
        DROP DATABASE IF EXISTS umahkawan;
        CREATE DATABASE umahkawan;
        USE umahkawan;

        CREATE TABLE categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(50) NOT NULL,
            DESCRIPTION TEXT,
            display_order INT DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,
            NAME VARCHAR(100) NOT NULL,
            DESCRIPTION TEXT,
            price DECIMAL(10,2) NOT NULL,
            image_url VARCHAR(255),
            is_available BOOLEAN DEFAULT TRUE,
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );

        CREATE TABLE orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_number VARCHAR(30) UNIQUE NOT NULL,

            -- Data Pelanggan (Snapshot)
            customer_name VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            customer_email VARCHAR(100),
            customer_address TEXT NOT NULL,
            customer_city VARCHAR(100) DEFAULT 'Denpasar',
            postal_code VARCHAR(10),

            -- Status
            order_status ENUM(
                'pending','confirmed','processing','ready',
                'delivering','completed','cancelled'
            ) DEFAULT 'pending',

            payment_status ENUM('unpaid','paid') DEFAULT 'unpaid',
            payment_method ENUM('cash','transfer','qris') DEFAULT 'cash',

            -- Harga
            subtotal DECIMAL(10,2) NOT NULL,
            delivery_fee DECIMAL(10,2) DEFAULT 0,
            total DECIMAL(10,2) NOT NULL,

            -- Catatan
            notes TEXT,

            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,

            product_id INT NOT NULL,
            product_name VARCHAR(100) NOT NULL,

            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            notes TEXT,

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        );

        CREATE TABLE payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            payment_method ENUM('cash','transfer','qris') NOT NULL,
            payment_proof_url VARCHAR(255),
            payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            verified_at TIMESTAMP NULL,
            notes TEXT,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        );

        CREATE INDEX idx_orders_date ON orders(order_date);
        CREATE INDEX idx_orders_status ON orders(order_status);
        CREATE INDEX idx_order_items_order ON order_items(order_id);

    ```
    *   Insert dummy data :

    ```sql
    INSERT INTO categories (id, name, description, display_order) VALUES
    (1, 'Rujak', 'Rujak Bali dengan bumbu khas', 1),
    (2, 'Tipat Cantok', 'Tipat dan sayur cantok khas Bali', 2),
    (3, 'Plecing', 'Plecing kangkung dan sayuran', 3),
    (4, 'Drinks', 'Minuman segar pelengkap', 4);


    INSERT INTO products 
(category_id, NAME, DESCRIPTION, price, image_url, is_available, display_order)
VALUES
    (1,'Rujak Kuah Pindang','Segar pedas gurih khas kuah pindang Bali.',8000,'img/rjkkuahpindang.jpg',1,1),
    (1,'Rujak Gula','Manis asam segar dengan sambal gula merah.',8000,'img/bg2.jpg',1,2),
    (1,'Rujak Colek','Buah segar dicolek sambal pedas manis.',8000,'img/gambar_1.jpg',1,3),
    (1,'Rujak Bulung','Rumput laut segar dengan kuah pindang khas.',10000,'img/bulungboni.jpeg',1,4),
    (1,'Rujak Kacang Manis','Perpaduan buah segar dan bumbu kacang lembut.',10000,'img/gambar4.jpg',1,5),

    (2,'Tipat Cantok','Lontong sayur khas Bali dengan bumbu kacang gurih.',10000,'img/cantok lengkap.jpg',1,1),
    (2,'Tipat Cantok Telur Ayam','Tipat cantok lezat dengan tambahan telur ayam.',14000,'img/tipat_cantok2.png',1,2),
    (2,'Sayur Cantok','Sayur rebus segar disiram bumbu kacang khas.',8000,'img/sayurcatok.jpg',1,3),
    (2,'Sayur Cantok Telur Ayam','Sayur cantok nikmat dengan telur ayam rebus.',12000,'img/syrtlrcantok.jpg',1,4),
    (2,'Sayur Cantok + Mie Goreng + Telur','Paduan sayur, mie goreng, dan telur spesial.',20000,'img/cantok lengkap.jpg',1,5),

    (3,'Sayur Plecing','Sayur segar dengan sambal plecing khas Bali.',8000,'img/plecinglur.jpg',1,1),
    (3,'Sayur Plecing Telur Ayam','Sayur plecing nikmat dengan tambahan telur ayam.',12000,'img/syrplecing.jpg',1,2),
    (3,'Bulung Boni','Rumput laut segar dengan kuah pindang pedas.',8000,'img/bulungboni.jpeg',1,3),
    (3,'Bulung Boni Cantok','Bulung boni disiram bumbu kacang gurih pedas.',14000,'img/bulungctk.jpeg',1,4),
    (3,'Tipat Plecing','Lontong sayur plecing segar dengan sambal khas.',10000,'img/tptplecing.jpg',1,5),

    (4,'Es Extra Joss Susu','Segarnya susu berpadu energi dari Extra Joss.',5000,'img/images.jpeg',1,1),
    (4,'Es Gula','Minuman manis sederhana yang menyegarkan hari Anda.',5000,'img/esgulabali.jpg',1,2),
    (4,'Es Daluman','Cincau hijau khas Bali dengan santan dan gula.',7000,'img/esdaluman.jpg',1,3),
    (4,'Es Tape','Tape singkong manis segar berpadu es dingin.',7000,'img/estape.jpeg',1,4),
    (4,'Es Susu','Kesegaran susu dingin yang lembut dan manis.',6000,'img/essusu.webp',1,5);

    ```

4.  **Configuration**:
    *   Open `config.php` and update the database credentials if necessary:
        ```php
        $host = "localhost";
        $username = "root";
        $password = ""; // Your DB password
        $database = "umahkawan";
        ```

5.  **Run the Project**:
    *   Open your browser and navigate to `http://localhost/UmahKawan` (or your configured virtual host).

## 📂 Project Structure

```
UmahKawan/
├── css/             # Stylesheets (Bootstrap, custom styles)
├── js/              # JavaScript files
├── img/             # Images for products and UI
├── fonts/           # Icons and fonts
├── config.php       # Database connection
├── functions.php    # Helper functions
├── index.php        # Homepage
├── menu.php         # Menu page
├── cart.php         # Shopping cart
├── checkout.php     # Checkout form
├── process-checkout.php # Order processing logic
└── ...
```

## 👨‍💻 Credits

Developed by **Gerald Hizkia** and the Team.
*Umah Kawan - Everyday is Rujak Day*