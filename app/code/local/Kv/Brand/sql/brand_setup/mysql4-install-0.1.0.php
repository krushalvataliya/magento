<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
--
DROP TABLE IF EXISTS `{$this->getTable('brand')}`;
CREATE TABLE `{$this->getTable('brand')}` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `{$this->getTable('brand')}`
  ADD PRIMARY KEY (`brand_id`);

ALTER TABLE `{$this->getTable('brand')}`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;
");

$installer->endSetup();
