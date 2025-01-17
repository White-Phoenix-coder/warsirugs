

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(44) NOT NULL,
  `username` varchar(44) NOT NULL,
  `password` varchar(44) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO admin VALUES("1","admin@gmail.com","admin@gmail.com","13579","2023-08-14");



CREATE TABLE `contact` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `message` varchar(300) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO contact VALUES("17","kalam","kam.com@gmail.com","1233456789","kalm","2024-11-06 10:46:42");
INSERT INTO contact VALUES("20","Javid","jav@gmail.com","7894566123","javjufsakdjf","2024-11-06 10:53:02");
INSERT INTO contact VALUES("23","gafsdafsdf","jav@gmail.com","7894566123","javjufsakdjf","2024-11-06 10:53:46");
INSERT INTO contact VALUES("24","gsfdgvxvwrwersfsfsf","jav@gmail.com","7894566123","javjufsakdjf","2024-11-06 10:53:52");
INSERT INTO contact VALUES("25","bvcb","jav@gmail.com","7894566123","gasdff","2024-11-06 10:53:57");

