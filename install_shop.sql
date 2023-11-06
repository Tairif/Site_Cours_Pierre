/*
 Navicat Premium Data Transfer

 Source Server         : Virtual Shiva
 Source Server Type    : MariaDB
 Source Server Version : 100519
 Source Host           : localhost:3306
 Source Schema         : sp_dw_1223

 Target Server Type    : MariaDB
 Target Server Version : 100519
 File Encoding         : 65001

 Date: 01/08/2023 09:25:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_produit
-- ----------------------------
DROP TABLE IF EXISTS `t_produit`;
CREATE TABLE `t_produit`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_tva` int(11) NULL DEFAULT NULL,
  `fk_promotion` int(11) NULL DEFAULT NULL,
  `fk_user` int(11) NULL DEFAULT NULL,
  `prixHT` decimal(10, 2) NULL DEFAULT NULL,
  `poids` decimal(10, 2) NULL DEFAULT NULL,
  `date_creation` bigint(20) NULL DEFAULT NULL,
  `date_modification` bigint(20) NULL DEFAULT NULL,
  `date_debut_promotion` bigint(20) NULL DEFAULT NULL,
  `date_fin_promotion` bigint(20) NULL DEFAULT NULL,
  `isActif` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_produit_image
-- ----------------------------
DROP TABLE IF EXISTS `t_produit_image`;
CREATE TABLE `t_produit_image`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_produit` int(11) NULL DEFAULT NULL,
  `nom_fichier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_produit_rayon
-- ----------------------------
DROP TABLE IF EXISTS `t_produit_rayon`;
CREATE TABLE `t_produit_rayon`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_produit` int(11) NULL DEFAULT NULL,
  `fk_rayon` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_produit_stock
-- ----------------------------
DROP TABLE IF EXISTS `t_produit_stock`;
CREATE TABLE `t_produit_stock`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_produit` int(11) NULL DEFAULT NULL,
  `fk_stock` int(11) NULL DEFAULT NULL,
  `qte` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_produit_trad
-- ----------------------------
DROP TABLE IF EXISTS `t_produit_trad`;
CREATE TABLE `t_produit_trad`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_produit` int(11) NULL DEFAULT NULL,
  `fk_langue` int(11) NULL DEFAULT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description_courte` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description_longue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_promotion
-- ----------------------------
DROP TABLE IF EXISTS `t_promotion`;
CREATE TABLE `t_promotion`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reduction` decimal(10, 2) NULL DEFAULT NULL,
  `isActif` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_promotion_trad
-- ----------------------------
DROP TABLE IF EXISTS `t_promotion_trad`;
CREATE TABLE `t_promotion_trad`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_promotion` int(11) NULL DEFAULT NULL,
  `fk_langue` int(11) NULL DEFAULT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_rayon
-- ----------------------------
DROP TABLE IF EXISTS `t_rayon`;
CREATE TABLE `t_rayon`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isActif` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_rayon_trad
-- ----------------------------
DROP TABLE IF EXISTS `t_rayon_trad`;
CREATE TABLE `t_rayon_trad`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_rayon` int(11) NULL DEFAULT NULL,
  `fk_langue` int(11) NULL DEFAULT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_stock
-- ----------------------------
DROP TABLE IF EXISTS `t_stock`;
CREATE TABLE `t_stock`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isActif` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_tva
-- ----------------------------
DROP TABLE IF EXISTS `t_tva`;
CREATE TABLE `t_tva`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_tva` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `value` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
