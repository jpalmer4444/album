/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  jasonpalmer
 * Created: Oct 12, 2016
 */

CREATE TABLE album (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    artist varchar(100) NOT NULL, 
    title varchar(100) NOT NULL
);

INSERT INTO album (artist, title) VALUES ('The Military Wives', 'In My Dreams');
INSERT INTO album (artist, title) VALUES ('Adele', '21');
INSERT INTO album (artist, title) VALUES ('Bruce Springsteen', 'Wrecking Ball (Deluxe)');
INSERT INTO album (artist, title) VALUES ('Lana Del Rey', 'Born To Die');
INSERT INTO album (artist, title) VALUES ('Gotye', 'Making Mirrors');

CREATE TABLE users (
    username varchar(100) PRIMARY KEY,
    password varchar(100) NOT NULL,
    sales_attr_id integer NOT NULL
);

INSERT INTO users (username, password, sales_attr_id) 
VALUES('jpalmer', '$2y$10$BaoRbZVUPtpZlhRJxd2dYeXEGf71LshO2AFWs6xlfYqKb6v5DgTjC', 1);
INSERT INTO users (username, password, sales_attr_id) 
VALUES('dtanzer', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 2);
INSERT INTO users (username, password, sales_attr_id) 
VALUES('jdowns', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 3);

CREATE TABLE roles (
    role varchar(25) PRIMARY KEY,
    description varchar(100) NOT NULL
);

INSERT INTO roles (role, description) 
VALUES('sales', 'Salespeople Access');

INSERT INTO roles (role, description) 
VALUES('admin', 'Admin Access');

CREATE TABLE user_role_xref (
    role varchar(25) NOT NULL,
    username varchar(100) NOT NULL,
    PRIMARY KEY(role, username)
);

INSERT INTO user_role_xref VALUES('admin', 'jpalmer');
INSERT INTO user_role_xref VALUES('sales', 'dtanzer');
INSERT INTO user_role_xref VALUES('sales', 'jdowns');

CREATE TABLE `session` (
    `id` char(32),
    `name` char(32),
    `modified` int,
    `lifetime` int,
    `data` text,
     PRIMARY KEY (`id`, `name`)
);
