-- Adminer 4.6.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE ``;

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `CartID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProductID_fk` int(10) unsigned NOT NULL,
  `UserID_fk` int(10) unsigned NOT NULL,
  PRIMARY KEY (`CartID`),
  KEY `ProductID_fk` (`ProductID_fk`),
  KEY `UserID_fk` (`UserID_fk`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`ProductID_fk`) REFERENCES `products` (`ProductID`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`UserID_fk`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `ProductID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Price` decimal(15,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `ImageName` varchar(255) DEFAULT NULL,
  `SalePrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `products` (`ProductID`, `ProductName`, `Description`, `Price`, `Quantity`, `ImageName`, `SalePrice`) VALUES
(1,	'Carolina Panthers Blue Winter Hat',	'A nice blue and white winter hat with the panther''s logo to keep you cozy in the cold.',	9.99,	8,	'pantherHat.jpg',	0.00),
(2,	'Carolina Panthers Blue Polo',	'A nice blue Nike Dri-FIT polo shirt that can be worn for any special occasion.',	69.99,	31,	'pantherPolo.jpg',	0.00),
(3,	'Carolina Panthers Black Performance Shorts',	'Enjoy a nice, loose Nike Dri-FIT short with the panther''s logo.',	29.99,	209,	'pantherShort.jpg',	14.99),
(4,	'Carolina Panthers Black Rain Jacket',	'A black athletic rain jacket that has the panther logo on it and is fully waterproof.',	49.99,	16,	'pantherRainJacket.jpg',	0.00),
(5,	'Carolina Panthers Short Sleeve Shirt',	'A grey Nike Dri-FIT shirt with a large panther logo on the chest.',	24.99,	57,	'pantherShirt.jpg',	0.00),
(6,	'Carolina Panthers Cam Newton Jersey',	'An authentic Cam Newton color rush jersey.',	99.99,	21,	'pantherJersey.jpg',	0.00),
(7,	'Charlotte Hornets Kemba Walker Jersey',	'An authentic Kemba Walker Jordan brand Swingman jersey.',	99.99,	325,	'hornetsJersey.jpg',	79.99),
(8,	'Charlotte Hornets Long Sleeve Shirt',	'A nice throwback classic with the old Hornets logo.',	18.99,	122,	'hornetsLongSleeve.jpg',	0.00),
(9,	'Charlotte Hornets Sweatshirt',	'Enjoy a warm purple hooded sweatshirt with the Hornets logo on the chest.',	59.99,	42,	'hornetsSweatshirt.jpg',	0.00),
(10,	'Charlotte Hornets Socks',	'A pair of black and turquoise stance socks.',	8.99,	73,	'hornetsSocks.jpg',	0.00),
(11,	'Charlotte Hornets Muggsy Bogues Jersey',	'Represent the Charlotte Hornets with an old hardwood classic jersey.',	109.99,	35,	'hornetsClassicJersey.jpg',	0.00),
(12,	'Charlotte Hornets Autographed Basketball',	'An authentic Wilson autograph basketball signed by Frank Kaminsky.',	39.99,	129,	'hornetsBasketball.jpg',	0.00),
(13,	'Carolina Hurricanes Jordan Staal Jersey',	'Authentic red Reebok jersey that you can wear to every game.',	110.99,	566,	'hurricanesJersey.jpg',	99.99),
(14,	'Carolina Hurricanes Black 1/4 Zip',	'An athletic fitted black 1/4 zip with the Hurricane logo.',	79.99,	50,	'hurricanesQuarterZip.jpg',	0.00),
(15,	'Carolina Hurricanes Red Winter Hat',	'A nice red and black winter hat with the Hurricane''s logo to keep you cozy when its cold.',	14.99,	42,	'hurricanesWinterHat.jpg',	0.00),
(16,	'Carolina Hurricanes Hockey Puck',	'An authentic hockey puck for you to play with on the ice rink or even in the backyard.',	19.99,	68,	'hurricanesPuck.jpg',	0.00),
(17,	'Carolina Hurricanes Red Backpack',	'A bright red backpack that you can use to carry your school books.',	34.99,	44,	'hurricanesBag.jpg',	0.00),
(18,	'Carolina Hurricanes Pajama Pants',	'A red and black cotten pajama pant that will keep you warm in bed.',	29.99,	26,	'hurricanesPajamaPants.jpg',	0.00);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `UserType` varchar(25) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Passwords are hashed... password for each user is their usertype */
INSERT INTO `user` (`UserID`, `Username`, `Password`, `UserType`) VALUES
(1,	'user',	'21232f297a57a5a743894a0e4a801fc3',	'admin'),
(2,	'jdoe',	'91ec1f9324753048c0096d036a694f86',	'customer');