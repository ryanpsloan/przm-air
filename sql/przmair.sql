DROP TABLE IF EXISTS transaction;
DROP TABLE IF EXISTS traveler;
DROP TABLE IF EXISTS ticket;
DROP TABLE IF EXISTS flight;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
	userId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	email VARCHAR(64) NOT NULL,
	passwordHash CHAR(128) NOT NULL,
	salt CHAR(64) NOT NULL,
	authToken CHAR(32),
	PRIMARY KEY(userId),
	UNIQUE(email)
);

CREATE TABLE profile (
	profileId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	userId INT UNSIGNED NOT NULL,
	userFirstName VARCHAR(75) NOT NULL,
	userMiddleName VARCHAR(75),
	userLastName VARCHAR(75) NOT NULL,
	dateOfBirth DATE NOT NULL,
	customerToken VARCHAR(130),
	PRIMARY KEY (profileId),
	INDEX(userId),
	FOREIGN KEY (userId) REFERENCES user (userId)
);

CREATE TABLE flight (
	flightId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	flightNumber VARCHAR(15),
	originAirport VARCHAR(10) NOT NULL,
	destinationAirport VARCHAR(10) NOT NULL,
	departureTime DATETIME NOT NULL,
	arrivalTime DATETIME,
	totalAvailableTickets INT UNSIGNED NOT NULL,
	totalTicketsSold INT UNSIGNED NOT NULL,
	PRIMARY KEY (flightId),
	UNIQUE(flightNumber)

);

CREATE TABLE ticket (
	confirmationNumber VARCHAR(10),
	price DECIMAL UNSIGNED,
	flightId INT UNSIGNED NOT NULL,
	profileId INT UNSIGNED NOT NULL,
	travelerId INT UNSIGNED NOT NULL,
	INDEX(flightId),
	FOREIGN KEY (flightId) REFERENCES flight (flightId),
	INDEX(profileId),
	FOREIGN KEY (profileId) REFERENCES profile (profileId),
	INDEX(travelerId),
	FOREIGN KEY (travelerId) REFERENCES traveler (travelerId),
	PRIMARY KEY (profileId, travelerId, flightId),
	UNIQUE (confirmationNumber)
);

CREATE TABLE traveler (
	travelerId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	travelerFirstName VARCHAR(75) NOT NULL,
	travelerMiddleName VARCHAR(75),
	travelerLastName VARCHAR(75) NOT NULL,
	travelerDateOfBirth DATE NOT NULL,
	profileId INT UNSIGNED NOT NULL,
	PRIMARY KEY (travelerId),
	FOREIGN KEY (profileId) REFERENCES profile(profileId),
	INDEX(profileId)
);

CREATE TABLE transaction (
	transactionId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	profileId INT UNSIGNED NOT NULL,
	amount DOUBLE UNSIGNED,
	dateApproved DATETIME,
	cardToken VARCHAR(124),
	stripeToken VARCHAR(124),
	PRIMARY KEY (transactionId),
	INDEX(profileId),
	FOREIGN KEY (profileId) REFERENCES profile (profileId)

);