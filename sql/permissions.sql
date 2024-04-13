CREATE TABLE `permissions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `role_id` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;