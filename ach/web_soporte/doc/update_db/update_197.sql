CREATE TABLE `lh_generic_bot_repeat_restrict` (`id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, `chat_id` bigint(20) NOT NULL, `trigger_id` bigint(20), `counter` int(11) DEFAULT '0', KEY `chat_id_trigger_id` (`chat_id`,`trigger_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;