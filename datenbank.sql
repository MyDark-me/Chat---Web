
CREATE TABLE IF NOT EXISTS `Gruppendatenbank` (
	`ID` INT NOT NULL AUTO_INCREMENT,
	`Name` VARCHAR(32) NOT NULL,
	`Displayname` VARCHAR(32) DEFAULT NULL,
	`Color` VARCHAR(20) DEFAULT 'gray',
	`Icon` VARCHAR(100) DEFAULT 'http://localhost/api/v1/icons/default.png',
    PRIMARY KEY (`ID`)
);

INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (1, 'default', 'Default', 'gray', 'http://localhost/api/v1/icons/default.png');
INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (2, 'supporter', 'Supporter', 'green', 'http://localhost/api/v1/icons/default.png');
INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (3, 'moderator', 'Moderator', 'purple', 'http://localhost/api/v1/icons/default.png');
INSERT INTO `Gruppendatenbank`(`ID`, `Name`, `Displayname`, `Color`, `Icon`) VALUES (4, 'administrator', 'Administrator', 'red', 'http://localhost/api/v1/icons/default.png');

CREATE TABLE IF NOT EXISTS `Nutzerdatenbank` (
	`ID` INT NOT NULL AUTO_INCREMENT,
	`Username` VARCHAR(32) NOT NULL,
	`Displayname` VARCHAR(32) DEFAULT NULL,
	`Password` VARCHAR(255) NOT NULL,
	`Email` VARCHAR(255) NOT NULL,
	`Icon` VARCHAR(100) DEFAULT 'http://localhost/api/v1/icons/default.png',
	`isOnline` BOOLEAN,
	`GroupID` INT DEFAULT '1',
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`GroupID`) REFERENCES `Gruppendatenbank`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Logindatabase` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `UserID` INT NOT NULL,
    `lastlogin` datetime DEFAULT NOW(),
    `ipaddress` int(11) unsigned DEFAULT NULL,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`UserID`) REFERENCES `Nutzerdatenbank`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Tokendatabase` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `LoginID` INT NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `endtime` Timestamp DEFAULT NOW(),
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`LoginID`) REFERENCES `Logindatabase`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Messagedatenbank` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `UserID` INT NOT NULL,
    `Timestamp` Timestamp NOT NULL,
    `LoginID` INT NOT NULL,
    `Content` VARCHAR(500) NOT NULL,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`UserID`) REFERENCES `Nutzerdatenbank`(`ID`),
    FOREIGN KEY (`LoginID`) REFERENCES `Logindatabase`(`ID`)
);

CREATE TABLE IF NOT EXISTS `Failedloginsdatabase` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) NOT NULL,
  `ip_address` int(11) unsigned DEFAULT NULL,
  `attempted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Registerrequestdatabase` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` int(11) unsigned DEFAULT NULL,
  `attempted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;