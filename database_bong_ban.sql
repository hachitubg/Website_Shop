
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
    ('Giày'),
    ('Vợt'),
    ('Quần áo');

INSERT INTO Products (CategoryID, ProductName, Description, Price, Quantity) VALUES
    (1, 'GIÀY THÔNG MINH SPEED ART', 'GIÀY THÔNG MINH SPEED ART', 1350000, 50),
    (1, 'Mizuno Crossmatch Plio RX4 line', 'Mizuno Crossmatch Plio RX4 line', 1500000, 20),
    (2, 'Razer Carbon L8', 'Razer Carbon L8', 600000, 40),
    (2, 'KORBEL', 'Versatile training shoes with a supportive design', 1250000, 40),
    (2, 'KONG LINGHUI', 'CỐT VỢT NGANG', 1800000, 40),
    (3, 'Áo Xiom F1 T12/2018 M3', 'Áo Xiom F1 T12/2018 M3 Đẹp', 160000, 40),
    (3, 'Quần Mizuno F1 T11/2018 M1', 'Áo Xiom F1 T12/2018 M3 Đẹp', 120000, 40),
    (3, 'Áo XIOM T1/2018 M12', 'Áo XIOM T1/2018 M12Áo XIOM T1/2018 M12', 650000, 10);

INSERT INTO ProductImages (ProductID, ImageURL) VALUES
    (1, 'https://hunghabongban.com.vn/images/products/20428498_1537096596355224_870738819_n.jpg'),
    (1, 'https://hunghabongban.com.vn/images/products/20427698_1537096593021891_766379969_n.jpg'),
    (1, 'https://hunghabongban.com.vn/images/products/20427804_1537096586355225_1433392070_n.jpg'),
    (2, 'https://hunghabongban.com.vn/images/products/46440572_2327209307349220_1711116804677435392_n.jpg'),
    (3, 'https://hunghabongban.com.vn/images/products/15045715_1253730831358470_1412577900_n.jpg'),
    (4, 'https://hunghabongban.com.vn/images/products/petr%20korbel%201-500x500.jpg'),
    (5, 'https://hunghabongban.com.vn/images/products/30740184_1819950838069797_130812248431001600_n.jpg'),
    (6, 'https://hunghabongban.com.vn/images/products/46434982_717903361899140_2776948467149307904_n.jpg'),
    (7, 'https://hunghabongban.com.vn/images/products/45805281_255950098603052_5281261940880965632_n.jpg'),
    (8, 'https://hunghabongban.com.vn/images/products/31131795_1826911320707082_5968978658436579328_n.jpg'),
    (2, 'https://hunghabongban.com.vn/images/products/46352683_876830299107908_5295818538529849344_n.jpg');
