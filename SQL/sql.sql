----------Name Database:facebookchatnew
CREATE DATABASE facebookchatnew CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;


--Table users
CREATE TABLE users(
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(30),
    user_email VARCHAR(50),
    user_password  VARCHAR(100),
    user_date VARCHAR(100),
    user_gender VARCHAR(20),
    user_image VARCHAR(10),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--table 
CREATE TABLE user_details(
    login_details_id INT(11) NOT NULL AUTO_INCREMENT,
    id INT(11) ,
    last_activity TIMESTAMP,
    is_type ENUM('no','yes'),
    PRIMARY KEY (login_details_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--friend_request
CREATE TABLE friend_request(
    id INT(11) AUTO_INCREMENT,
    sender INT(11) ,
    receiver INT(11),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--friends
CREATE TABLE friends(
    id INT(11) AUTO_INCREMENT,
    user_one INT(11),
    user_two INT(11),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--comment
CREATE TABLE comment(
    id INT(11) AUTO_INCREMENT,
    user_id INT(11),
    message TEXT,
    imagepath VARCHAR(100),
    user_email TEXT,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--chat_message
CREATE TABLE chat_message(
    chat_message_id INT(11) AUTO_INCREMENT,
    to_user_id INT(11),
    from_user_id INT(11),
    chat_message TEXT,
    timestamp TIMESTAMP,
    status INT(11),
    PRIMARY KEY (chat_message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;