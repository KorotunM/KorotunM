CREATE TABLE Person(
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  fio varchar(150) NOT NULL DEFAULT '',
  tel varchar(12) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  bornday varchar(8) NOT NUll DEFAULT '',
  gender varchar(20) Not NULL DEFAULT '',
  bio varchar(300) NOT NULL DEFAULT '',
  checked boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY(id)
);
CREATE TABLE Lang(
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  language varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY(id)
);
CREATE TABLE person_lang(
id int(10) unsigned NOT NULL AUTO_INCREMENT,
id_u int(10) unsigned Not NUll DEFAULT 0,
id_l int(10) unsigned Not NUll DEFAULT 0,
PRIMARY KEY(id)
);

INSERT INTO Lang(language) VALUES ('C');
INSERT INTO Lang(language) VALUES ('Pascal');
INSERT INTO Lang(language) VALUES ('Scala');
INSERT INTO Lang(language) VALUES ('C++');
INSERT INTO Lang(language) VALUES ('Java');
INSERT INTO Lang(language)VALUES ('Python');
INSERT INTO Lang(language) VALUES ('JavaScript');
INSERT INTO Lang(language) VALUES ('PHP');
INSERT INTO Lang(language) VALUES ('Hascel');
INSERT INTO Lang(language) VALUES ('Clojure');
INSERT INTO Lang(language) VALUES ('Prolog');