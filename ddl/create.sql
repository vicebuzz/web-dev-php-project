
-- create tables in empty DB

-- entities: User, Booking, Activity
/*
CREATE IF NOT EXISTS TABLE User(
    userID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    userPassword VARCHAR(255) NOT NULL,
    registrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

CREATE IF NOT EXISTS TABLE Booking(
    bookingID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    bookingCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

CREATE IF NOT EXISTS TABLE Activity(
    actitivyID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    activityName VARCHAR(255) NOT NULL,
    placesAvailable INTEGER(255) NOT NULL,
    activityDate DATE
)

CREATE IF NOT EXISTS TABLE UserToBooking(
    userID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    bookingID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    PRIMARY KEY (userID, bookingID),
    FOREIGN KEY (userID) REFERENCES User(userID),
    FOREIGN KEY (bookingID) REFERENCES Booking(bookingID)
)

CREATE IF NOT EXISTS TABLE BookingToActivivy(
    bookingID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    activityID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    PRIMARY KEY (bookingID, activityID),
    FOREIGN KEY (bookingID) REFERENCES Booking(bookingID),
    FOREIGN KEY (activityID) REFERENCES Activity(activityID)
)
*/

-- Create the Club table
CREATE TABLE Club (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(255) NOT NULL,
    motto VARCHAR(255),
    club_description TEXT,
    category VARCHAR(50)
);

-- Create the Activity table
CREATE TABLE Activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activity_name VARCHAR(255) NOT NULL,
    activity_description TEXT,
    places_available INT,
    activity_date DATE
);

-- Create the User table
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subscription_type ENUM('Free', 'Premium', 'Pro'),
    preferred_categories TEXT
);

-- Create the Booking table
CREATE TABLE Booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_booked DATE,
    number_of_places_booked INT
);

-- Create the relationships between the tables
-- Many clubs can have many activities
CREATE TABLE Club_Activity (
    club_activity_id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT,
    activity_id INT,
    FOREIGN KEY (club_id) REFERENCES Club(id),
    FOREIGN KEY (activity_id) REFERENCES Activity(id)
);

-- Many activities can have many bookings
CREATE TABLE Activity_Booking (
    activity_booking_id INT AUTO_INCREMENT PRIMARY KEY,
    activity_id INT,
    booking_id INT,
    FOREIGN KEY (activity_id) REFERENCES Activity(id),
    FOREIGN KEY (booking_id) REFERENCES Booking(id)
);

-- Many users can have many bookings
CREATE TABLE User_Booking (
    booking_user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    booking_id INT,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (booking_id) REFERENCES Booking(id)
);
