
-- create tables in empty DB

-- entities: Users, Booking, Activity

CREATE TABLE Users(
    userID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    userPassword VARCHAR(255) NOT NULL,
    registrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Booking(
    bookingID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    bookingCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Activity(
    activityID INTEGER(255) AUTO_INCREMENT PRIMARY KEY,
    activityName VARCHAR(255) NOT NULL,
    placesAvailable INTEGER(255) NOT NULL,
    activityDate DATE
);

CREATE TABLE UserToBooking(
    userID INTEGER(255) AUTO_INCREMENT,
    bookingID INTEGER(255),
    PRIMARY KEY (userID, bookingID),
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (bookingID) REFERENCES Booking(bookingID)
);

CREATE TABLE BookingToActivivy(
    bookingID INTEGER(255) AUTO_INCREMENT,
    activityID INTEGER(255),
    PRIMARY KEY (bookingID, activityID),
    FOREIGN KEY (bookingID) REFERENCES Booking(bookingID),
    FOREIGN KEY (activityID) REFERENCES Activity(activityID)
);