CREATE TABLE IF NOT EXISTS `Client` (
  `clientId` INT NOT NULL,
  `address` VARCHAR(255) NULL,
  `phoneNumber` VARCHAR(45) NULL,
  PRIMARY KEY (`clientId`),
  UNIQUE INDEX `orderId_UNIQUE` (`orderId` ASC),
  `status` tinyint(1))
ENGINE = InnoDB;