#
#Structure des tables 'prefix_magalerie'
#


CREATE TABLE `magalerie` (
    `id`       INT(11)              NOT NULL AUTO_INCREMENT,
    `uid`      INT(5)               NOT NULL DEFAULT '0',
    `userid`   INT(5)               NOT NULL DEFAULT '0',
    `img`      VARCHAR(40)          NOT NULL DEFAULT '',
    `clic`     INT(11)              NOT NULL DEFAULT '0',
    `note`     INT(14)              NOT NULL DEFAULT '0',
    `vote`     TINYINT(14) UNSIGNED NOT NULL DEFAULT '0',
    `valid`    INT(1)                        DEFAULT NULL,
    `date`     INT(14)                       DEFAULT NULL,
    `comments` INT(11) UNSIGNED              DEFAULT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`)
)
    ENGINE = ISAM;

CREATE TABLE `magalerie_cat` (
    `id`     TINYINT(4)    NOT NULL AUTO_INCREMENT,
    `cid`    TINYINT(4)    NOT NULL DEFAULT '0',
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

