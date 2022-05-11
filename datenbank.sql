
CREATE TABLE IF NOT EXISTS `Gruppendatenbank` (
	`ID` INT NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(50) NOT NULL,
	`Displayname` VARCHAR(10) NOT NULL DEFAULT NULL,
	`Color` VARCHAR(10) NOT NULL DEFAULT 'gray',
	`Icon` VARCHAR(10) NOT NULL DEFAULT 'http://localhost/api/v1/icons/default.png',
    PRIMARY KEY (`ID`)
);

INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (1, 'default', 'Default', 'gray', 'http://localhost/api/v1/icons/default.png');
INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (2, 'supporter', 'Supporter', 'blue', 'http://localhost/api/v1/icons/default.png');
INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (3, 'moderator', 'Moderator', 'red', 'http://localhost/api/v1/icons/default.png');

CREATE TABLE IF NOT EXISTS `Nutzerdatenbank` (
	`ID` INT NOT NULL AUTO_INCREMENT,
	`Username` VARCHAR(50) NOT NULL,
	`Displayname` VARCHAR(10) NOT NULL DEFAULT NULL,
	`Password` VARCHAR(10) NOT NULL,
	`Email` VARCHAR(10) NOT NULL,
	`Icon` VARCHAR(10) NOT NULL DEFAULT 'http://localhost/api/v1/icons/default.png',
	`isOnline` BOOLEAN,
	`GroupID` INT NOT NULL DEFAULT '1',
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`GroupID`) REFERENCES `Gruppendatenbank`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Logindatabase` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `UserID` INT NOT NULL,
    `lastlogin` datetime NOT NULL,
    `ipaddress` int(11) unsigned DEFAULT NULL,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`UserID`) REFERENCES `Nutzerdatenbank`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Tokendatabase` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `LoginID` INT NOT NULL,
    `token` VARCHAR(10) NOT NULL,
    `endtime` VARCHAR(10) NOT NULL DEFAULT NOW(),
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`LoginID`) REFERENCES `Logindatabase`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Messagedatenbank` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `UserID` INT NOT NULL,
    `Timestamp` Timestamp NOT NULL,
    `LoginID` INT NOT NULL,
    `Content` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`ID`)
    FOREIGN KEY (`UserID`) REFERENCES `Nutzerdatenbank`(`ID`)
    FOREIGN KEY (`LoginID`) REFERENCES `Nutzerdatenbank`(`ID`)
);