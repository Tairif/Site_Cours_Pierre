/*
Installation BDD - IFR Dev Web 1223
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_commande
-- ----------------------------
DROP TABLE IF EXISTS `t_commande`;
CREATE TABLE `t_commande`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` bigint(20) NULL DEFAULT NULL,
  `fk_user` int(11) NULL DEFAULT NULL,
  `fk_statut` int(11) NULL DEFAULT NULL,
  `n_commande` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_commande_produit
-- ----------------------------
DROP TABLE IF EXISTS `t_commande_produit`;
CREATE TABLE `t_commande_produit`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_commande` int(11) NULL DEFAULT NULL,
  `fk_produit` int(11) NULL DEFAULT NULL,
  `qte` int(11) NULL DEFAULT NULL,
  `prixHT` decimal(10, 2) NULL DEFAULT NULL,
  `tva` decimal(10, 2) NULL DEFAULT NULL,
  `prixTTC` decimal(10, 2) NULL DEFAULT NULL,
  `reduction` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;



-- ----------------------------
-- Table structure for t_interface
-- ----------------------------
DROP TABLE IF EXISTS `t_interface`;
CREATE TABLE `t_interface`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user` int(11) NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_langue
-- ----------------------------
DROP TABLE IF EXISTS `t_langue`;
CREATE TABLE `t_langue`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_langue
-- ----------------------------
INSERT INTO `t_langue` VALUES (1, 'Francais', 'fr.png');
INSERT INTO `t_langue` VALUES (2, 'Anglais', 'gb.png');

-- ----------------------------
-- Table structure for t_menu
-- ----------------------------
DROP TABLE IF EXISTS `t_menu`;
CREATE TABLE `t_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ordre` int(11) NULL DEFAULT NULL,
  `fk_parent` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_menu_trad
-- ----------------------------
DROP TABLE IF EXISTS `t_menu_trad`;
CREATE TABLE `t_menu_trad`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_menu` int(11) NULL DEFAULT NULL,
  `fk_langue` int(11) NULL DEFAULT NULL,
  `libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for t_pays
-- ----------------------------
DROP TABLE IF EXISTS `t_pays`;
CREATE TABLE `t_pays`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_pays
-- ----------------------------
INSERT INTO `t_pays` VALUES (1, 'France');

-- ----------------------------
-- Table structure for t_photo
-- ----------------------------
DROP TABLE IF EXISTS `t_photo`;
CREATE TABLE `t_photo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photographie` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `titre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ordre` int(11) NULL DEFAULT NULL,
  `fk_user` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
-- Table structure for t_statut_commande
-- ----------------------------
DROP TABLE IF EXISTS `t_statut_commande`;
CREATE TABLE `t_statut_commande`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isActif` tinyint(4) NULL DEFAULT NULL,
  `isStock` tinyint(4) NULL DEFAULT NULL,
  `isBlock` tinyint(4) NULL DEFAULT NULL,
  `isDefault` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_statut_commande
-- ----------------------------
INSERT INTO `t_statut_commande` VALUES (1, 1, 0, 0, 1);
INSERT INTO `t_statut_commande` VALUES (2, 1, 0, 0, 0);
INSERT INTO `t_statut_commande` VALUES (3, 1, 1, 0, 0);
INSERT INTO `t_statut_commande` VALUES (4, 1, 0, 1, 0);
INSERT INTO `t_statut_commande` VALUES (5, 1, 0, 1, 0);

-- ----------------------------
-- Table structure for t_statut_commande_trad
-- ----------------------------
DROP TABLE IF EXISTS `t_statut_commande_trad`;
CREATE TABLE `t_statut_commande_trad`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_statut_commande` int(11) NULL DEFAULT NULL,
  `fk_langue` int(11) NULL DEFAULT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_statut_commande_trad
-- ----------------------------
INSERT INTO `t_statut_commande_trad` VALUES (1, 1, 1, 'En cours');
INSERT INTO `t_statut_commande_trad` VALUES (2, 1, 2, 'In progress');
INSERT INTO `t_statut_commande_trad` VALUES (3, 2, 1, 'En attente de Paiement');
INSERT INTO `t_statut_commande_trad` VALUES (4, 2, 2, ' Waiting for payment');
INSERT INTO `t_statut_commande_trad` VALUES (5, 3, 1, 'Payée');
INSERT INTO `t_statut_commande_trad` VALUES (6, 3, 2, ' Paid');
INSERT INTO `t_statut_commande_trad` VALUES (7, 4, 1, 'Expédiée');
INSERT INTO `t_statut_commande_trad` VALUES (8, 4, 2, ' Shipped');
INSERT INTO `t_statut_commande_trad` VALUES (9, 5, 1, 'Annulée');
INSERT INTO `t_statut_commande_trad` VALUES (10, 5, 2, 'Canceled');

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

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `prenom` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `adresse_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `adresse_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cp` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fk_ville` int(11) NULL DEFAULT NULL,
  `fk_pays` int(11) NULL DEFAULT NULL,
  `fk_langue` int(11) NULL DEFAULT NULL,
  `login` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `session` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `isAdmin` tinyint(4) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES (1, 'Back Office', 'Administrateur', 'Adresse 1', 'Adresse 2', '97423', 1, 1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'img_64cbdd8835167.png', NULL, 1);

-- ----------------------------
-- Table structure for t_ville
-- ----------------------------
DROP TABLE IF EXISTS `t_ville`;
CREATE TABLE `t_ville`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_ville
-- ----------------------------
INSERT INTO `t_ville` VALUES (1, 'Saint Pierre');

SET FOREIGN_KEY_CHECKS = 1;
