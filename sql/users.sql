CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `expiry_time` INT NOT NULL default 3600,
    `role_id` TINYINT NULL DEFAULT 2,
    `confirmation_token` VARCHAR(255) NOT NULL,
    `reset_token` VARCHAR(255) NULL,
    `options` JSON NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `users` ADD INDEX (`email`);
