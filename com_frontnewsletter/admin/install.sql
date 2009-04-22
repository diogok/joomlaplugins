CREATE TABLE IF NOT EXISTS #__frontnewsletter_newsletters (
        id int(11) auto_increment,
        content text,
        subject text,
        date long,
        primary key(id)
        ) engine=MyIsam ;

CREATE TABLE IF NOT EXISTS #__frontnewsletter_sent (
        id int(11) auto_increment,
        id_newsletter int(11),
        date long,
        content text,
        subject text,
        primary key(id)
        ) engine=MyIsam ;

CREATE TABLE IF NOT EXISTS #__frontnewsletter_sent_users (
        id int(11) auto_increment,
        id_sent int(11),
        id_user int(11),
        email text,
        primary key(id)
        ) engine=MyIsam ;
