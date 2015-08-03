CREATE TABLE IF NOT EXISTS `Client` (
  `clientId` INT NOT NULL,
  `address` VARCHAR(255) NULL,
  `phoneNumber` VARCHAR(45) NULL,
  `amount` DECIMAL(9,2),
  PRIMARY KEY (`clientId`),
  `status` tinyint(1))
ENGINE = InnoDB;