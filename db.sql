DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
	`id` int NOT NULL AUTO_INCREMENT,
	`fname` varchar(200) DEFAULT NULL,
	`lname` varchar(200) DEFAULT NULL,
	`emp_code` varchar(20) DEFAULT '',
	`username` varchar(200) DEFAULT NULL,
	`email` varchar(200) DEFAULT NULL,
	`position` varchar(200) DEFAULT NULL,
	`password` varchar(200) DEFAULT NULL,
	`salt` varchar(3) DEFAULT NULL,
	`account_status` varchar(20) DEFAULT NULL,
	`site_role_id` int DEFAULT NULL,
	`change_pw` varchar(1) DEFAULT '',
	`approver_level` int DEFAULT '0',
	`primary_location` int DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `users` VALUES (1,'TGI','ADMIN','','tgiadmin','john@tgioa.com','System Admin','$argon2id$v=19$m=65536,t=4,p=1$TDQvZktLMDF3VklBdU5WTw$34T1ST3KZjEOVrr3hdvjVsEIvdCIn71Rf4rh4en3qF8','1','Active',99,'',4,0);



DROP TABLE IF EXISTS `user_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_session` (
	`hash` varchar(50) NOT NULL,
	`user_id` int DEFAULT NULL,
	`session_data` longtext,
	`creation_time` datetime DEFAULT NULL,
	`last_active` datetime DEFAULT NULL,
	`ip` varchar(200) DEFAULT NULL,
	`ua` varchar(200) DEFAULT NULL,
	`host` varchar(200) DEFAULT NULL,
	PRIMARY KEY (`hash`),
	UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `orders`;
create table orders(
	id int auto_increment,
    order_last_updated datetime,
	DWDOCID int unique,
	DWSTOREUSER varchar(100),
	DWSTOREDATETIME datetime,
	DWMODUSER varchar(100),
	DWMODDATETIME datetime,
	DWLASTACCESSUSER varchar(100),
	DWLASTACCESSDATETIME datetime,
	CUSTOMER_NAME varchar(200),
	ORDER_TYPE varchar(100),
    YEAR int,
    MONTH int,
	STATUS varchar(100),
	DATE_CREATED date,	
    STUDENTCOUNT int default 0,
	primary key (id)
);

DROP TABLE IF EXISTS `order_line_item`;
create table order_line_item(
	id int auto_increment,
	order_id int,
    order_date date,
	item varchar(200),
    item_serving_size_uom varchar(50),
    item_serving_size_amt float(12,3),
	primary key (id)
);


