DROP TABLE IF EXISTS itinerary;
DROP TABLE IF EXISTS airport;
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
	UNIQUE (userId),
	FOREIGN KEY (userId) REFERENCES user (userId)
);

CREATE TABLE flight (
	flightId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	templateId INT UNSIGNED NOT NULL,
	flightNumber VARCHAR(15),
	originAirport VARCHAR(10) NOT NULL,
	destinationAirport VARCHAR(10) NOT NULL,
	departureTime DATETIME NOT NULL,
	arrivalTime DATETIME NOT NULL,
	duration TIME NOT NULL,
	totalSeatsOnPlane INT UNSIGNED NOT NULL,
	totalTicketsSold INT UNSIGNED NOT NULL,
	totalAvailableSeats INT UNSIGNED NOT NULL,
	totalNumConfirmedSeats INT UNSIGNED NOT NULL,
	INDEX(originAirport),
	INDEX(destinationAirport),
	INDEX(departureTime),
	INDEX(arrivalTime),
	INDEX(duration),
	UNIQUE(flightNumber),
	PRIMARY KEY (flightId),
	FOREIGN KEY (templateId) REFERENCES template(templateId)
);

create TABLE template (
	templateId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	flightNumber VARCHAR(15) NOT NULL,
	originAirport VARCHAR(10) NOT NULL,
	destinationAirport VARCHAR(10) NOT NULL,
	departureTime DATETIME NOT NULL,
	arrivalTime DATETIME NOT NULL,
	duration TIME NOT NULL
);

CREATE TABLE ticket (
	ticketId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	confirmationNumber VARCHAR(10),
	price DECIMAL(5,2) UNSIGNED,
	flightId INT UNSIGNED NOT NULL,
	profileId INT UNSIGNED NOT NULL,
	travelerId INT UNSIGNED NOT NULL,
	UNIQUE (confirmationNumber),

	INDEX(flightId),
	INDEX(profileId),
	INDEX(travelerId),

	PRIMARY KEY (ticketId),
	FOREIGN KEY (flightId) REFERENCES flight (flightId),
	FOREIGN KEY (profileId) REFERENCES profile (profileId),
	FOREIGN KEY (travelerId) REFERENCES traveler (travelerId),

);

CREATE TABLE ticket_flight (
	flightId INT UNSIGNED NOT NULL,
	ticketId INT UNSIGNED NOT NULL,
	INDEX(flightId),
	INDEX(ticketId),
	PRIMARY KEY (flightId,ticketId),
	FOREIGN KEY flightId REFERENCES flight(flightId),
	FOREIGN KEY ticketId REFERENCES ticket(ticketId)

);

CREATE TABLE traveler (
	travelerId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	travelerFirstName VARCHAR(75) NOT NULL,
	travelerMiddleName VARCHAR(75),
	travelerLastName VARCHAR(75) NOT NULL,
	travelerDateOfBirth DATE NOT NULL,
	profileId INT UNSIGNED NOT NULL,
	INDEX(profileId),
	PRIMARY KEY (travelerId),
	FOREIGN KEY (profileId) REFERENCES profile(profileId)

);

CREATE TABLE transaction (
	transactionId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	profileId INT UNSIGNED NOT NULL,
	amount DECIMAL(5,2) UNSIGNED,
	dateApproved DATETIME,
	cardToken VARCHAR(124),
	stripeToken VARCHAR(124),
	PRIMARY KEY (transactionId),
	INDEX(profileId),
	FOREIGN KEY (profileId) REFERENCES profile (profileId)

);

CREATE TABLE airport (
	airportId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	airportCode VARCHAR(10) NOT NULL,
	airportDescription VARCHAR(100) NOT NULL,
	airportSearchField VARCHAR(100) NOT NULL,
	INDEX(airportSearchField),
	PRIMARY KEY (airportId)
);

