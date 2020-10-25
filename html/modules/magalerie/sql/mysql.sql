#
# Table structure for table 'prefix_magalerie'
#

CREATE TABLE `magalerie` (
    `id`    INT(11)              NOT NULL AUTO_INCREMENT,
    `uid`   INT(5)               NOT NULL DEFAULT '0',
    `cat`   VARCHAR(25)          NOT NULL DEFAULT '',
    `img`   VARCHAR(30)          NOT NULL DEFAULT '',
    `clic`  INT(11)              NOT NULL DEFAULT '0',
    `note`  INT(14)              NOT NULL DEFAULT '0',
    `vote`  TINYINT(14) UNSIGNED NOT NULL DEFAULT '0',
    `valid` INT(1)                        DEFAULT NULL,
    `date`  INT(14)                       DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `img` (`img`),
    UNIQUE KEY `id` (`id`)
)
    ENGINE = ISAM;

CREATE TABLE `magalerie_cat` (
    `id`     TINYINT(4)    NOT NULL AUTO_INCREMENT,
    `cat`    VARCHAR(25)   NOT NULL DEFAULT '',
    `img`    VARCHAR(150)  NOT NULL DEFAULT '',
    `coment` VARCHAR(250)  NOT NULL DEFAULT '',
    `clic`   INT(11)       NOT NULL DEFAULT '0',
    `alea`   INT(1)                 DEFAULT '1',
    `valid`  INT(1)                 DEFAULT '1',
    `date`   TIMESTAMP(14) NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = ISAM;

CREATE TABLE `magalerie_comments` (
    `id`    INT(10)     NOT NULL AUTO_INCREMENT,
    `lid`   INT(10)     NOT NULL DEFAULT '0',
    `uid`   INT(10)     NOT NULL DEFAULT '0',
    `titre` VARCHAR(75) NOT NULL DEFAULT '',
    `texte` TEXT        NOT NULL,
    `date`  INT(10)     NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)
    ENGINE = ISAM;
