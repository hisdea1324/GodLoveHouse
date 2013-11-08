DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
    `id`          INT(4) NOT NULL AUTO_INCREMENT,
    `number`      VARCHAR(50),
    `bank`        VARCHAR(50),
    `name`        VARCHAR(50) NOT NULL,
    `method`      CHAR(10) NOT NULL,
    `nid`         CHAR(13),
    `userid`      VARCHAR(50) NOT NULL,
    `sendDate`    INT,
    `expectDate`  INT,
    `regDate`     int(4) not null default 0,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS `attachFile`;
CREATE TABLE `attachFile` (
    `id`      INT(4) NOT NULL AUTO_INCREMENT,
    `userid`  VARCHAR(30) NOT NULL,
    `name`    VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS `board`;
CREATE TABLE `board` (
    `id`            INT(4) NOT NULL AUTO_INCREMENT,
    `groupId`       VARCHAR(50) NOT NULL,
    `title`         VARCHAR(256) NOT NULL,
    `contents`      TEXT NOT NULL,
    `password`      VARCHAR(50) NOT NULL,
    `regDate`       int(4) not null default 0,
    `editDate`      int(4) not null default 0,
    `userid`        VARCHAR(50) NOT NULL,
    `countView`     INT NOT NULL default 0,
    `countComment`  INT NOT NULL default 0,
    `answerId`      INT NOT NULL,
    `answerNum`     INT NOT NULL default 0,
    `answerLv`      INT NOT NULL default 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS boardGroup;
CREATE TABLE boardGroup (
    groupId        VARCHAR(50) NOT NULL,
    managerId      VARCHAR(50) NOT NULL,
    authReadLv     INT NOT NULL,
    authWriteLv    INT NOT NULL,
    authCommentLv  INT NOT NULL,
    countList      INT NOT NULL DEFAULT 0,
    `name`         VARCHAR(50) NOT NULL,
	PRIMARY KEY (`groupId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS `code`;
CREATE TABLE `code` (
    `id`    INT(4) NOT NULL AUTO_INCREMENT,
    `code`  CHAR(5) NOT NULL,
    `name`  VARCHAR(50) NOT NULL,
    `type`  VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS debug_query_list;
CREATE TABLE debug_query_list (
    `id`          INT(4) NOT NULL AUTO_INCREMENT,
    `query`       TEXT,
    regDate     int(4) not null default 0,
    errMessage  TEXT,
    errNumber   INT null DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS family;
CREATE TABLE family (
    `id`            INT(4) NOT NULL AUTO_INCREMENT,
    userid        VARCHAR(50) NOT NULL,
    followuserid  VARCHAR(50) NOT NULL,
    familyType    CHAR(5) NOT NULL,
    regDate       int(4) not null default 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS familyComment;
CREATE TABLE familyComment (
    `id`          INT(4) NOT NULL AUTO_INCREMENT,
    parentId    int(4) not null default -1,
    hostuserid  VARCHAR(50) NOT NULL,
    followId    VARCHAR(50) NOT NULL,
    `comments`    TEXT NOT NULL,
    regDate     int(4) not null default 0,
    `secret`      int(4) not null default 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS hospital;
CREATE TABLE hospital (
    hospitalId    INT(4) NOT NULL AUTO_INCREMENT,
    hospitalName  VARCHAR(50),
    assocName     VARCHAR(50),
    address1      VARCHAR(100) NOT NULL,
    address2      VARCHAR(100) NOT NULL,
    zipcode       CHAR(6),
    regionCode    CHAR(5),
    `explain`       TEXT NOT NULL,
    userid        VARCHAR(50),
    manager1      VARCHAR(50),
    manager2      VARCHAR(50),
    contact1      VARCHAR(50),
    contact2      VARCHAR(50),
    price         INT null DEFAULT 0,
    personLimit   INT NOT NULL DEFAULT 0,
    regDate       int NOT NULL DEFAULT 0,
    `status`        CHAR(5) NOT NULL DEFAULT 'S2001',
    homepage      VARCHAR(256),
    documentId    INT NOT NULL DEFAULT -1,
    `document`      VARCHAR(50),
    imageId1      INT NOT NULL DEFAULT -1,
    imageId2      INT NOT NULL DEFAULT -1,
    imageId3      INT NOT NULL DEFAULT -1,
    imageId4      INT NOT NULL DEFAULT -1,
	PRIMARY KEY (`hospitalId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS house;
CREATE TABLE house (
    houseId       INT(4) NOT NULL AUTO_INCREMENT,
    assocName     VARCHAR(50),
    address1      VARCHAR(100) NOT NULL,
    address2      VARCHAR(100) NOT NULL,
    zipcode       CHAR(6),
    regionCode    CHAR(5),
    `explain`       TEXT NOT NULL,
    userid        VARCHAR(50),
    manager1      VARCHAR(50),
    manager2      VARCHAR(50),
    contact1      VARCHAR(50),
    contact2      VARCHAR(50),
    price         INT null DEFAULT 0,
    personLimit   INT NOT NULL DEFAULT 0,
    roomLimit     INT NOT NULL DEFAULT 0,
    houseName     VARCHAR(50) NOT NULL,
    buildingType  INT NOT NULL DEFAULT 1,
    regDate       INT NOT NULL DEFAULT 0,
    `status`        CHAR(5) NOT NULL DEFAULT 'S2001',
    homepage      VARCHAR(256),
    roomCount     INT NOT NULL DEFAULT 0,
    documentId    INT NOT NULL DEFAULT -1,
    document      VARCHAR(50),
	PRIMARY KEY (`houseId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS missionary;
CREATE TABLE missionary (
    userid          VARCHAR(50) NOT NULL,
    missionName     VARCHAR(50),
    church          VARCHAR(50),
    churchContact   VARCHAR(50),
    ngo             VARCHAR(50),
    ngoContact      VARCHAR(50),
    nationCode      CHAR(5) NOT NULL,
    accountNo       VARCHAR(50),
    bank            VARCHAR(50),
    accountName     VARCHAR(50),
    homepage        VARCHAR(100),
    manager         VARCHAR(50),
    managerContact  VARCHAR(50),
    managerEmail    VARCHAR(50),
    memo            TEXT,
    prayList        TEXT,
    familyCount     INT NOT NULL DEFAULT 0,
    approval        INT NOT NULL DEFAULT 0,
    imageId         INT,
    flagFamily      INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS missionary_family;
CREATE TABLE missionary_family (
    `id`        INT(4) NOT NULL AUTO_INCREMENT,
    userid    VARCHAR(30) NOT NULL,
    `name`      VARCHAR(30),
    age       INT NOT NULL,
    sex       VARCHAR(20) NOT NULL DEFAULT '0',
    `relation`  VARCHAR(20) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS requestAddInfo;
CREATE TABLE requestAddInfo (
    reqId       INT NOT NULL,
    userid      VARCHAR(50) NOT NULL,
    `status`      CHAR(5) NOT NULL DEFAULT '05001',
    dueDate     DATETIME NOT NULL,
    nationCode  CHAR(5) NOT NULL,
	PRIMARY KEY (`reqId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS requestInfo;
CREATE TABLE requestInfo (
    reqId        INT(4) NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(256) NOT NULL,
    `explain`      TEXT,
    supportType  CHAR(5) NOT NULL,
    regDate      INT NOT NULL DEFAULT 0,
    imageId      INT,
	PRIMARY KEY (`reqId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS requestItem;
CREATE TABLE requestItem (
    reqItemId   INT(4) NOT NULL AUTO_INCREMENT,
    reqId       INT NOT NULL,
    `item`        VARCHAR(256) NOT NULL,
    `descript`    VARCHAR(1024) NOT NULL,
    cost        INT NOT NULL DEFAULT 0,
    userid      VARCHAR(50),
    sendStatus  CHAR(5),
	PRIMARY KEY (`reqItemId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS reservation;
CREATE TABLE reservation (
    reservationNo  INT(4) NOT NULL AUTO_INCREMENT,
    userid         VARCHAR(50) NOT NULL,
    roomId         INT,
    hospitalId     INT,
    reservStatus   CHAR(5) NOT NULL,
    startDate      INT NOT NULL DEFAULT 0,
    endDate        INT NOT NULL DEFAULT 0,
    regDate        INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`reservationNo`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS room;
CREATE TABLE room (
    roomId    INT(4) NOT NULL AUTO_INCREMENT,
    roomName  VARCHAR(50) NOT NULL,
    `limit`     INT NOT NULL,
    `explain`   TEXT,
    network   VARCHAR(100) NOT NULL,
    kitchen   VARCHAR(100) NOT NULL,
    laundary  VARCHAR(100) NOT NULL,
    fee       INT NOT NULL,
    houseId   VARCHAR(50) NOT NULL,
    document  VARCHAR(100),
    imageId1  INT NOT NULL DEFAULT -1,
    imageId2  INT NOT NULL DEFAULT -1,
    imageId3  INT NOT NULL DEFAULT -1,
    imageId4  INT NOT NULL DEFAULT -1,
	PRIMARY KEY (`roomId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS supportInfo;
CREATE TABLE supportInfo (
    supId        INT(4) NOT NULL AUTO_INCREMENT,
    userid       VARCHAR(50) NOT NULL,
    sumPrice     INT NOT NULL DEFAULT 0,
    supportType  CHAR(5) NOT NULL,
    `status`       CHAR(5) NOT NULL,
    `name`         VARCHAR(50),
    jumin        CHAR(13) NOT NULL,
    phone        VARCHAR(50),
    mobile       VARCHAR(50),
    email        VARCHAR(100),
    zipcode      CHAR(6),
    address1     VARCHAR(100),
    address2     VARCHAR(100),
    regDate      INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`supId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS supportItem;
CREATE TABLE supportItem (
    supItemId  INT(4) NOT NULL AUTO_INCREMENT,
    supId      INT NOT NULL,
    reqId      INT NOT NULL,
    cost       INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`supItemId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS test;
CREATE TABLE test (
    `id`    INT(4) NOT NULL AUTO_INCREMENT,
    `name`  VARCHAR(50),
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS useRoom;
CREATE TABLE useRoom (
    roomId       INT NOT NULL,
    resevedDate  INT NOT NULL DEFAULT 0,
    roomStatus   CHAR(5) NOT NULL,
	PRIMARY KEY (`roomId`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    userid         VARCHAR(50) NOT NULL,
    password       VARCHAR(256) NOT NULL,
    passQuest      TINYINT NOT NULL,
    passAnswer     VARCHAR(50) NOT NULL,
    `name`           VARCHAR(50) NOT NULL,
    nick           VARCHAR(50),
    userLv         INT NOT NULL DEFAULT 0,
    email          VARCHAR(100) NOT NULL,
    jumin          CHAR(13) NOT NULL,
    address1       VARCHAR(100),
    address2       VARCHAR(100),
    zipcode        CHAR(6) NOT NULL,
    phone          VARCHAR(50),
    mobile         VARCHAR(50),
    msgOk          INT NOT NULL DEFAULT 0,
    registDate     INT NOT NULL DEFAULT 0,
    updateDate     INT NOT NULL DEFAULT 0,
    LastLoginDate  INT NOT NULL DEFAULT 0,
	PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
