-- Создание таблиц
CREATE TABLE Authors (
    AuthorID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Country VARCHAR(50) NOT NULL
);

CREATE TABLE Books (
    BookID INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(150) NOT NULL,
    Genre VARCHAR(50),
    AuthorID INT,
    FOREIGN KEY (AuthorID) REFERENCES Authors(AuthorID)
);

CREATE TABLE Publishers (
    PublisherID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Country VARCHAR(50) NOT NULL
);

CREATE TABLE BookPublisher (
    BookPublisherID INT PRIMARY KEY AUTO_INCREMENT,
    BookID INT,
    PublisherID INT,
    FOREIGN KEY (BookID) REFERENCES Books(BookID),
    FOREIGN KEY (PublisherID) REFERENCES Publishers(PublisherID)
);

CREATE TABLE Reviews (
    ReviewID INT PRIMARY KEY AUTO_INCREMENT,
    BookID INT,
    ReviewerName VARCHAR(100),
    Rating INT CHECK (Rating >= 1 AND Rating <= 5),
    ReviewText TEXT,
    FOREIGN KEY (BookID) REFERENCES Books(BookID)
);

-- Заполнение таблиц
INSERT INTO Authors (Name, Country)
SELECT CONCAT('Author ', n), CASE WHEN n % 2 = 0 THEN 'USA' ELSE 'UK' END
FROM (SELECT @row := @row + 1 AS n FROM (SELECT 0 UNION ALL SELECT 1) t1, (SELECT 0 UNION ALL SELECT 1) t2, (SELECT 0 UNION ALL SELECT 1) t3, (SELECT 0 UNION ALL SELECT 1) t4, (SELECT 0 UNION ALL SELECT 1) t5, (SELECT 0 UNION ALL SELECT 1) t6, (SELECT @row := 0) t7) t8 LIMIT 100;

INSERT INTO Books (Title, Genre, AuthorID)
SELECT 
    CONCAT('Book ', n), 
    CASE WHEN n % 3 = 0 THEN 'Fiction' ELSE 'Non-Fiction' END, 
    (SELECT AuthorID FROM Authors ORDER BY RAND() LIMIT 1)
FROM (
    SELECT @row := @row + 1 AS n 
    FROM (SELECT 0 UNION ALL SELECT 1) t1, 
         (SELECT 0 UNION ALL SELECT 1) t2, 
         (SELECT 0 UNION ALL SELECT 1) t3, 
         (SELECT 0 UNION ALL SELECT 1) t4, 
         (SELECT 0 UNION ALL SELECT 1) t5, 
         (SELECT 0 UNION ALL SELECT 1) t6, 
         (SELECT @row := 0) t7
) t8 LIMIT 100;

INSERT INTO Publishers (Name, Country)
SELECT CONCAT('Publisher ', n), CASE WHEN n % 2 = 0 THEN 'Germany' ELSE 'France' END
FROM (SELECT @row := @row + 1 AS n FROM (SELECT 0 UNION ALL SELECT 1) t1, (SELECT 0 UNION ALL SELECT 1) t2, (SELECT 0 UNION ALL SELECT 1) t3, (SELECT 0 UNION ALL SELECT 1) t4, (SELECT 0 UNION ALL SELECT 1) t5, (SELECT 0 UNION ALL SELECT 1) t6, (SELECT @row := 0) t7) t8 LIMIT 100;

INSERT INTO BookPublisher (BookID, PublisherID)
SELECT 
    (SELECT BookID FROM Books ORDER BY RAND() LIMIT 1), 
    (SELECT PublisherID FROM Publishers ORDER BY RAND() LIMIT 1)
FROM (
    SELECT @row := @row + 1 AS n 
    FROM (SELECT 0 UNION ALL SELECT 1) t1, 
         (SELECT 0 UNION ALL SELECT 1) t2, 
         (SELECT 0 UNION ALL SELECT 1) t3, 
         (SELECT 0 UNION ALL SELECT 1) t4, 
         (SELECT 0 UNION ALL SELECT 1) t5, 
         (SELECT 0 UNION ALL SELECT 1) t6, 
         (SELECT @row := 0) t7
) t8 LIMIT 100;

INSERT INTO Reviews (BookID, ReviewerName, Rating, ReviewText)
SELECT 
    (SELECT BookID FROM Books ORDER BY RAND() LIMIT 1), 
    CONCAT('Reviewer ', n), 
    FLOOR(1 + RAND() * 5), 
    CONCAT('Review text for book ', n)
FROM (
    SELECT @row := @row + 1 AS n 
    FROM (SELECT 0 UNION ALL SELECT 1) t1, 
         (SELECT 0 UNION ALL SELECT 1) t2, 
         (SELECT 0 UNION ALL SELECT 1) t3, 
         (SELECT 0 UNION ALL SELECT 1) t4, 
         (SELECT 0 UNION ALL SELECT 1) t5, 
         (SELECT 0 UNION ALL SELECT 1) t6, 
         (SELECT @row := 0) t7
) t8 LIMIT 100;


SELECT 
    a.Name AS AuthorName, a.Country AS AuthorCountry, 
    b.Title AS BookTitle, b.Genre AS BookGenre, 
    p.Name AS PublisherName, p.Country AS PublisherCountry, 
    r.ReviewerName, r.Rating, r.ReviewText
FROM Authors a
JOIN Books b ON a.AuthorID = b.AuthorID
JOIN BookPublisher bp ON b.BookID = bp.BookID
JOIN Publishers p ON bp.PublisherID = p.PublisherID
JOIN Reviews r ON b.BookID = r.BookID;

SELECT 
    (SELECT Name FROM Authors WHERE AuthorID = b.AuthorID) AS AuthorName,
    (SELECT Country FROM Authors WHERE AuthorID = b.AuthorID) AS AuthorCountry,
    b.Title AS BookTitle, b.Genre AS BookGenre,
    (SELECT Name FROM Publishers WHERE PublisherID = bp.PublisherID) AS PublisherName,
    (SELECT Country FROM Publishers WHERE PublisherID = bp.PublisherID) AS PublisherCountry,
    r.ReviewerName, r.Rating, r.ReviewText
FROM Books b
JOIN BookPublisher bp ON b.BookID = bp.BookID
JOIN Reviews r ON b.BookID = r.BookID;

SELECT 
    a.Name AS AuthorName, a.Country AS AuthorCountry, 
    b.Title AS BookTitle, b.Genre AS BookGenre, 
    p.Name AS PublisherName, p.Country AS PublisherCountry, 
    r.ReviewerName, r.Rating, r.ReviewText
FROM Authors a
JOIN Books b ON a.AuthorID + 0 = b.AuthorID -- Prevent index usage
JOIN BookPublisher bp ON b.BookID + 0 = bp.BookID
JOIN Publishers p ON bp.PublisherID + 0 = p.PublisherID
JOIN Reviews r ON b.BookID + 0 = r.BookID;

SELECT 
    a.Name AS AuthorName, a.Country AS AuthorCountry, 
    b.Title AS BookTitle, b.Genre AS BookGenre, 
    p.Name AS PublisherName, p.Country AS PublisherCountry, 
    r.ReviewerName, r.Rating, r.ReviewText
FROM Authors a
JOIN Books b ON a.AuthorID = ABS(b.AuthorID) 
JOIN BookPublisher bp ON b.BookID = ABS(bp.BookID)
JOIN Publishers p ON bp.PublisherID = ABS(p.PublisherID)
JOIN Reviews r ON b.BookID = ABS(r.BookID);
