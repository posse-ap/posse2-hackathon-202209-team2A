DROP SCHEMA IF EXISTS posse;

CREATE SCHEMA posse;

USE posse;

DROP TABLE IF EXISTS events;

CREATE TABLE events (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(10) NOT NULL,
  start_at DATETIME NOT NULL,
  end_at DATETIME NOT NULL,
  detail TEXT,
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

INSERT INTO events SET name='ハッカソン', start_at='2022/09/08 10:00', end_at='2022/09/03 22:00';
INSERT INTO events SET name='遊び', start_at='2022/09/07 18:00', end_at='2022/09/07 22:00';
INSERT INTO events SET name='スペモク', start_at='2022/09/08 18:00', end_at='2022/09/08 22:00';
INSERT INTO events SET name='遊び', start_at='2022/09/09 18:00', end_at='2022/09/09 22:00', detail='みんなでいっぱい遊ぼうね！！何しよっか！！！';
INSERT INTO events SET name='横モク', start_at='2022/09/10 18:00', end_at='2022/09/10 22:00', detail='横でもくもく！ハッカソンお疲れ様！';
INSERT INTO events SET name='花火大会', start_at='2022/09/11 18:00', end_at='2022/09/11 22:00', detail='花火大会言ってないなああって思ったそこのあなた！一緒にいきましょ〜〜';
INSERT INTO events SET name='夏祭り', start_at='2022/09/11 18:00', end_at='2022/09/11 22:00', detail='金魚掬いしよーね！射的もしたいんですけど';
INSERT INTO events SET name='映画', start_at='2022/09/12 18:00', end_at='2022/09/12 22:00', detail='みんなで久しぶりにmovie nightしたいなああ----';
INSERT INTO events SET name='遊び', start_at='2022/09/13 18:00', end_at='2022/09/13 22:00';
INSERT INTO events SET name='海', start_at='2022/09/14 18:00', end_at='2022/09/14 22:00';
INSERT INTO events SET name='浅草', start_at='2022/09/15 18:00', end_at='2022/09/15 22:00';
INSERT INTO events SET name='横モク', start_at='2022/09/16 18:00', end_at='2022/09/16 22:00';
INSERT INTO events SET name='コミもく', start_at='2022/09/17 18:00', end_at='2022/09/06 22:00';
INSERT INTO events SET name='posseLab会', start_at='2022/09/18 18:00', end_at='2022/09/06 22:00';
INSERT INTO events SET name='たわーtoタワー', start_at='2022/09/19 18:00', end_at='2022/09/06 22:00';
INSERT INTO events SET name='池to海', start_at='2022/09/20 18:00', end_at='2022/09/06 22:00';
INSERT INTO events SET name='語る会', start_at='2022/09/21 18:00', end_at='2022/09/06 22:00';
INSERT INTO events SET name='睡眠もくもく会', start_at='2022/09/22 18:00', end_at='2022/09/06 22:00';
INSERT INTO events SET name='ありがとうの会', start_at='2022/09/23 18:00', end_at='2022/09/06 22:00';

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
  name VARCHAR(255),
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT DEFAULT 0
);

INSERT INTO users
SET
  email = "user@posse.com",
  name = "山田康介",
  password = sha1('pass'), 
  is_admin = 1;

INSERT INTO users
SET
  email = "user2@posse.com",
  name = "寺岡修馬",
  password = sha1('pass');

INSERT INTO 
  users 
SET
  email = "user3@posse.com",
  name = "大友裕太",
  password = sha1('pass');


-- パスワードリセット関連です。

DROP TABLE IF EXISTS user_password_reset;

CREATE TABLE
    user_password_reset (
        id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        pass_token VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

INSERT INTO user_password_reset(email, pass_token);

VALUES ("test@test.com", "test");