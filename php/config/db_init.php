<?php

    //USERS
    $db->prepare("
        CREATE TABLE ph_users (
            id int(10) NOT NULL AUTO_INCREMENT,
            username varchar(25),
            password varchar(256),
            email varchar(255),
            admin int(1) DEFAULT 0,
            confirmed int(1) DEFAULT 0,
            verified int(1) DEFAULT 0,
            private int(1) DEFAULT 1,
            banned int(3) DEFAULT 0,
            description varchar(255),
            security varchar(256),
            created int(20),
            conditions int(20),
            PRIMARY KEY(id)
        );
    ")->execute();

    //USERSETTINGS

    $db->prepare("
        CREATE TABLE ph_usersettings (
            id int(10),
            msg_email int(1),
            msg_push int(1),
            PRIMARY KEY(id)
        );
    ")->execute();

    //POSTS

    $db->prepare("
        CREATE TABLE ph_posts (
            id int(10) NOT NULL AUTO_INCREMENT,
            user int(10),
            type int(2),
            description TEXT,
            date int(20),
            PRIMARY KEY(id)
        );
    ")->execute();

    //FRIENDSHIPS

    $db->prepare("
        CREATE TABLE ph_friends (
            id int(10) NOT NULL AUTO_INCREMENT,
            user int(10),
            friend int(10),
            intensity int(4),
            PRIMARY KEY(id)
        );
    ")->execute();

    //LIST
?>