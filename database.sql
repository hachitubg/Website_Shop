
CREATE TABLE Customers (
    CustomerID INT PRIMARY KEY AUTO_INCREMENT,
    FullName VARCHAR(255) NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(100) NOT NULL,
    AvatarURL VARCHAR(255),
    PhoneNumber VARCHAR(15),
    Email VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryID INT,
    ProductName VARCHAR(255) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    Quantity INT NOT NULL
);

CREATE TABLE Categories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(255) NOT NULL
);

CREATE TABLE ProductImages (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ProductID INT,
    ImageURL VARCHAR(255) NOT NULL,
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

CREATE TABLE ShoppingCart (
    ShoppingCartID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Customers(CustomerID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ProductID INT,
    Quantity INT,
    TotalAmount DECIMAL(10, 2),
    PaymentStatus VARCHAR(50) DEFAULT 'Thành công',
    OrderDate DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO Categories (CategoryName) VALUES
    ('Running Shoes'),
    ('Training Gear'),
    ('Casual Wear');

INSERT INTO Products (CategoryID, ProductName, Description, Price, Quantity) VALUES
    (1, 'UltraBoost 21 Shoes', 'Responsive running shoes with a high-performance design', 180.00, 50),
    (1, 'NMD_R1 Shoes', 'Casual shoes with a distinctive look and cushioned feel', 130.00, 30),
    (2, 'Tiro 21 Track Jacket', 'A moisture-absorbing jacket with the iconic 3-Stripes design', 65.00, 20),
    (2, 'CrazyTrain Pro 3 Shoes', 'Versatile training shoes with a supportive design', 90.00, 40),
    (3, 'Essentials Linear Tee', 'A comfortable t-shirt with a clean, simple design', 25.00, 60),
    (3, 'Stan Smith Shoes', 'Iconic shoes with a minimalist design', 80.00, 15),
    (1, 'SolarBoost 3 Shoes', 'Neutral running shoes with a breathable, supportive design', 150.00, 25),
    (2, 'Alphaskin Tech Tights', 'Compression tights for training in cool weather', 55.00, 35),
    (3, 'Superstar Shoes', 'Classic shoes with a signature shell toe', 85.00, 20),
    (1, 'SenseBOOST Go Shoes', 'Lightweight and responsive shoes for city running', 110.00, 30);

INSERT INTO ProductImages (ProductID, ImageURL) VALUES
    (1, 'https://example.com/images/ultraboost_1.jpg'),
    (1, 'https://example.com/images/ultraboost_2.jpg'),
    (2, 'https://example.com/images/nmd_r1_1.jpg'),
    (2, 'https://example.com/images/nmd_r1_2.jpg'),
    (3, 'https://example.com/images/tiro_jacket_1.jpg'),
    (3, 'https://example.com/images/tiro_jacket_2.jpg'),
    (4, 'https://example.com/images/crazytrain_1.jpg'),
    (4, 'https://example.com/images/crazytrain_2.jpg'),
    (5, 'https://example.com/images/linear_tee_1.jpg'),
    (5, 'https://example.com/images/linear_tee_2.jpg');
