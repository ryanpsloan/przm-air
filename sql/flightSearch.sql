DROP PROCEDURE IF EXISTS spFlightSearchR;

CREATE PROCEDURE spFlightSearchR (IN startLoc VARCHAR(20), endLoc VARCHAR(20), departDate DATETIME, arrivalDate DATETIME)
PROC:BEGIN
	IF departDate >= arrivalDate THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Arrival date must be AFTER Departure date.';
		LEAVE PROC;
	END IF;

	CREATE TEMPORARY TABLE flightSearchR (
		startId INT,
		endId INT,
		path VARCHAR(256),
		INDEX (startId),
		INDEX (endId),
		INDEX (path)
	);

	INSERT INTO flightSearchR
	SELECT flightId, flightId, flightId
	FROM flight
	WHERE origin = startLoc
		AND departureDateTime >= departDate
		AND arrivalDateTime <= arrivalDate;

	REPEAT

		INSERT INTO flightSearchR
		SELECT R1.startId, F2.flightId, CONCAT(R1.path, ',', CAST(F2.flightId AS CHAR))
		FROM flightSearchR R1
		INNER JOIN flight F1 ON R1.endId = F1.flightId
		INNER JOIN flight F2 ON F1.destination = F2.origin
		LEFT JOIN flightSearchR R2 ON R2.path = CONCAT(R1.path, ',', CAST(F2.flightId AS CHAR))
		WHERE F1.destination <> endLoc
			AND F2.arrivalDateTime <= arrivalDate
			AND R2.path IS NULL;

	UNTIL (ROW_COUNT() = 0)
	END REPEAT
	;

	SELECT R.startId, R.endId, R.path, CHAR_LENGTH(path) - CHAR_LENGTH(REPLACE(path,',','')) AS Stops
	FROM flightSearchR R
	INNER JOIN flight F ON F.flightId = R.endId
	WHERE F.destination = endLoc
	ORDER BY Stops;
END;