SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS user;
CREATE TABLE user (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255),
  `balance` DECIMAL(10,2),
  `datetime` DATETIME
) ENGINE=InnoDB CHARSET=UTF8;

DROP TABLE IF EXISTS user_transaction;
CREATE TABLE user_transaction (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2),
  `order_id` INT UNSIGNED NULL,
  `datetime` DATETIME,
  INDEX by_user_id (user_id),
  FOREIGN KEY (user_id)
  REFERENCES user(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARSET=UTF8;