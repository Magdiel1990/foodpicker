-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: Magdiel Castillo   Database: foodday
-- ------------------------------------------------------
-- Server version	8.0.31

--
-- Table structure for table `categories`
--
DROP DATABASE IF EXISTS `foodday`;

CREATE DATABASE `foodday`;

USE `foodday`;

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;

CREATE TABLE `ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);

--
-- Table structure for table `diet`
--

DROP TABLE IF EXISTS `diet`;

CREATE TABLE `diet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);

--
-- Table structure for table `diet_details`
--

DROP TABLE IF EXISTS `diet_details`;

CREATE TABLE `diet_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dietid` int NOT NULL,
  `day` varchar(9) NOT NULL,
  `recipes` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_diet_diet_details` FOREIGN KEY (`dietid`) REFERENCES `diet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

--
-- Table structure for table `inglook`
--

DROP TABLE IF EXISTS `inglook`;

CREATE TABLE `inglook` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ingredientid` int NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_inglook` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;

CREATE TABLE `recipe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `categoryid` int NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ingredients` text,
  `cookingtime` int DEFAULT NULL,
  `url` varchar(300) NOT NULL UNIQUE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_recipe_categories` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);