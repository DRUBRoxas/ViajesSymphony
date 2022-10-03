-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema Buking
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Buking
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Buking` DEFAULT CHARACTER SET utf8 ;
USE `Buking` ;


-- -----------------------------------------------------
-- Table `Buking`.`Pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Buking`.`Pedidos` (
  `idPedidos` INT NOT NULL AUTO_INCREMENT,
  `user_email` VARCHAR(255) NOT NULL,
  `Product_idProduct` INT NOT NULL,
  PRIMARY KEY (`idPedidos`, `user_email`, `Product_idProduct`),
  CONSTRAINT `fk_Pedidos_user1`
    FOREIGN KEY (`user_email`)
    REFERENCES `Buking`.`user` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Pedidos_Product1`
    FOREIGN KEY (`Product_idProduct`)
    REFERENCES `Buking`.`Product` (`idProduct`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
