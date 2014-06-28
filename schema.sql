/*
If you want to reset your database, just put the two next line in the code.

DROP TABLE IF EXISTS tasks CASCADE;
DROP TABLE IF EXISTS tasklists CASCADE;
 */

CREATE TABLE IF NOT EXISTS tasklists(
  id int NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
)ENGINE=INNODB CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS tasks(
  id int NOT NULL AUTO_INCREMENT,
  parent int NOT NULL,
  title VARCHAR(255) NOT NULL,
  notes TEXT,
  due DATETIME,
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES tasklists(id) ON DELETE CASCADE
)ENGINE=INNODB CHARACTER SET = utf8;
