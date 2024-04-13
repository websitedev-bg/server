CREATE TABLE `media` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `size` VARCHAR(255) NULL,
    `src` VARCHAR(255) NOT NULL,
    `options` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `media` ADD INDEX `INDEX` (`src`);
ALTER TABLE `media` ADD UNIQUE `UNIQUE` (`src`);