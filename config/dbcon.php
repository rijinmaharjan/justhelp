<?php

if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '123123');
    define('DB_DATABASE', 'clothing');
    define('DB_PORT', 3307);
}
// define('DB_PORT', 3307);

// STEP 1: Connect to the server ONLY (Notice the 4th parameter is empty "")
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, "", DB_PORT);

if (!$conn) {
    die('Connection Failed: ' . mysqli_connect_error());
}

$createDatabaseQuery = 'CREATE DATABASE IF NOT EXISTS `' . DB_DATABASE . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci';
if (!mysqli_query($conn, $createDatabaseQuery)) {
    die('Database creation failed: ' . mysqli_error($conn));
}

if (!mysqli_select_db($conn, DB_DATABASE)) {
    die('Database selection failed: ' . mysqli_error($conn));
}

$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_ban TINYINT(1) NOT NULL DEFAULT 0,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$createProductsTable = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) DEFAULT NULL,
    name VARCHAR(191) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    size VARCHAR(120) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$createCartTable = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    size VARCHAR(50) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cart_user_id (user_id),
    INDEX idx_cart_product_id (product_id),
    CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$schemaQueries = [$createUsersTable, $createProductsTable, $createCartTable];

foreach ($schemaQueries as $query) {
    if (!mysqli_query($conn, $query)) {
        die('Table creation failed: ' . mysqli_error($conn));
    }
}

?>
