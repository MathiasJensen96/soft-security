CREATE DATABASE `securitydb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
use securitydb;
CREATE TABLE `user` (
  `email` varchar(50) NOT NULL,
  `password` varchar(45) NOT NULL,
  `role` varchar(5) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `order` (
  `id` int NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `User_email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Order_User1_idx` (`User_email`),
  CONSTRAINT `fk_Order_User1` FOREIGN KEY (`User_email`) REFERENCES `user` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `orderline` (
  `productId` int NOT NULL,
  `orderId` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`productId`,`orderId`),
  KEY `fk_Orderline_Product_idx` (`productId`),
  KEY `fk_Orderline_Order_idx` (`orderId`),
  CONSTRAINT `fk_Orderline_Order` FOREIGN KEY (`orderId`) REFERENCES `order` (`id`),
  CONSTRAINT `fk_Orderline_Product` FOREIGN KEY (`productId`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
