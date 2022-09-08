DROP SCHEMA IF EXISTS posse;
CREATE SCHEMA posse;
USE posse;

DROP TABLE IF EXISTS events;
CREATE TABLE events (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(10) NOT NULL,
  start_at DATETIME,
  end_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME
);

DROP TABLE IF EXISTS event_attendance;
CREATE TABLE event_attendance (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  event_id INT NOT NULL,
  user_id INT,
  status VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME
);


INSERT INTO events SET name='縦モク', start_at='2021/08/01 21:00', end_at='2021/08/01 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/02 21:00', end_at='2021/08/02 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/03 20:00', end_at='2021/08/03 22:00';
INSERT INTO events SET name='縦モク', start_at='2021/08/08 21:00', end_at='2021/08/08 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/09 21:00', end_at='2021/08/09 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/10 20:00', end_at='2021/08/10 22:00';
INSERT INTO events SET name='縦モク', start_at='2021/08/15 21:00', end_at='2021/08/15 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/16 21:00', end_at='2021/08/16 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/17 20:00', end_at='2021/08/17 22:00';
INSERT INTO events SET name='縦モク', start_at='2021/08/22 21:00', end_at='2021/08/22 23:00';
INSERT INTO events SET name='横モク', start_at='2021/08/23 21:00', end_at='2021/08/23 23:00';
INSERT INTO events SET name='スペモク', start_at='2021/08/24 20:00', end_at='2021/08/24 22:00';
INSERT INTO events SET name='遊び', start_at='2021/09/22 18:00', end_at='2021/09/22 22:00';
INSERT INTO events SET name='ハッカソン', start_at='2021/09/03 10:00', end_at='2021/09/03 22:00';
INSERT INTO events SET name='遊び', start_at='2022/09/07 18:00', end_at='2022/09/07 22:00';
INSERT INTO events SET name='スペモク', start_at='2022/09/08 18:00', end_at='2022/09/08 22:00';
INSERT INTO events SET name='遊び', start_at='2022/09/09 18:00', end_at='2022/09/09 22:00';
INSERT INTO events SET name='横モク', start_at='2022/09/10 18:00', end_at='2022/09/10 22:00';
INSERT INTO events SET name='花火大会', start_at='2022/09/11 18:00', end_at='2022/09/01 22:00';
INSERT INTO events SET name='スペモク', start_at='2022/09/12 18:00', end_at='2022/09/12 22:00';
INSERT INTO events SET name='遊び', start_at='2022/09/13 18:00', end_at='2022/09/13 19:00';
INSERT INTO events SET name='海', start_at='2022/09/14 18:00', end_at='2022/09/14 22:00';
INSERT INTO events SET name='浅草', start_at='2022/09/15 18:00', end_at='2022/09/15 22:00';
INSERT INTO events SET name='横モク', start_at='2022/09/16 18:00', end_at='2022/09/16 22:00';
INSERT INTO event_attendance SET event_id=1, user_id = 1, status="presence";
INSERT INTO event_attendance SET event_id=1, user_id = 2, status="presence";
INSERT INTO event_attendance SET event_id=1, user_id = 3, status="presence";
INSERT INTO event_attendance SET event_id=2, user_id = 1, status="presence";
INSERT INTO event_attendance SET event_id=2, user_id = 2, status="presence";
INSERT INTO event_attendance SET event_id=3, user_id = 1, status="presence";

DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,  
  name VARCHAR(255),
  github_id VARCHAR(255)
);

INSERT INTO users (email, password, name, github_id) VALUES ("user@posse.com","pass", "横山健人", "Y-K-15");
INSERT INTO users (email, password, name, github_id) VALUES ("user2@posse.com","pass", "西川航平", "random");
INSERT INTO users (email, password, name, github_id) VALUES ("user3@posse.com","pass", "冨永桃", "momo-0315");

