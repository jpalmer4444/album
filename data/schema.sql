/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    version integer,
    password varchar(100) NOT NULL,
    salespersonname varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    phone1 varchar(100) NOT NULL,
    sales_attr_id integer NOT NULL,
    last_login TIMESTAMP DEFAULT NULL
);

CREATE INDEX index_users_salespersonname
ON users (salespersonname);

CREATE INDEX index_users_sales_attr_id
ON users (sales_attr_id);

CREATE INDEX cmp_index_users_salespersonname_sales_attr_id
ON users (salespersonname, sales_attr_id);

CREATE INDEX cmp_index_users_username_salespersonname_sales_attr_id
ON users (username, salespersonname, sales_attr_id);

INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('jpalmer', '$2y$10$BaoRbZVUPtpZlhRJxd2dYeXEGf71LshO2AFWs6xlfYqKb6v5DgTjC', 'Cyndi Metallo', 183, 'cmetallo@fultonfishmarket.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('dtanzer', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Cyndi Metallo', 183, 'cmetallo@fultonfishmarket.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('jdowns', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Cyndi Metallo', 183, 'cmetallo@fultonfishmarket.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('cmetallo', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Cyndi Metallo', 183, 'cmetallo@fultonfishmarket.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('mspindler', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Cyndi Metallo', 183, 'cmetallo@fultonfishmarket.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('bzakrinski', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Bill Zakrinski', 206, 'bzak@meadedigital.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('iderfler', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Iris Derfler', 181, 'iderfler@meadedigital.com', '999-999-9999');
INSERT INTO users (username, password, salespersonname, sales_attr_id, email, phone1) 
VALUES('jmeade', '$2y$11$dNgq1cOKM4hEhuML8rwZD.XY195yLIz.i0.cnn92/EtnY2vl1PGrO', 'Jody Meade', 180, 'jody@meadedigital.com', '999-999-9999');

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
INSERT INTO user_role_xref VALUES('admin', 'dtanzer');
INSERT INTO user_role_xref VALUES('admin', 'jdowns');
INSERT INTO user_role_xref VALUES('admin', 'mspindler');
INSERT INTO user_role_xref VALUES('admin', 'cmetallo');
INSERT INTO user_role_xref VALUES('sales', 'bzakrinski');
INSERT INTO user_role_xref VALUES('sales', 'iderfler');
INSERT INTO user_role_xref VALUES('sales', 'jmeade');


CREATE TABLE `session` (
    `id` char(32),
    `name` char(32),
    `modified` int,
    `lifetime` int,
    `data` text,
     PRIMARY KEY (`id`, `name`)
);

CREATE TABLE `row_plus_items_page` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT, 
    `version` integer,
    `sku` VARCHAR(25),
    `productname` VARCHAR(255),
    `description` VARCHAR(255),
    `comment` VARCHAR(255),
    `option` VARCHAR(255),
    `qty` INTEGER,
    `wholesale` INTEGER,
    `retail` INTEGER,
    `overrideprice` INTEGER,
    `uom` VARCHAR(100),
    `status` BOOLEAN,
    `active` BOOLEAN,
    `saturdayenabled` BOOLEAN,
    `created` TIMESTAMP,
    `customerid` INTEGER,
    `salesperson` INTEGER,
     FOREIGN KEY(salesperson) REFERENCES users(username)
);

CREATE INDEX index_row_plus_items_page_sku
ON row_plus_items_page (sku);

CREATE INDEX index_row_plus_items_page_customer_id
ON row_plus_items_page (customerid);

CREATE TABLE `item_price_override` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT, 
    `version` integer,
    `sku` VARCHAR(25),
    `overrideprice` INTEGER,
    `active` BOOLEAN,
    `comment` VARCHAR(255),
    `option` VARCHAR(255),
    `created` TIMESTAMP,
    `customerid` INTEGER,
    `salesperson` INTEGER,
     FOREIGN KEY(salesperson) REFERENCES users(username)
);

CREATE INDEX index_item_price_override_sku
ON item_price_override (sku);

CREATE INDEX index_item_price_override_customer_id
ON item_price_override (customerid);

CREATE TABLE `pricing_override_report` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT, 
    `version` integer,
    `sku` VARCHAR(25),
    `product` VARCHAR(255),
    `description` VARCHAR(255),
    `comment` TEXT,
    `retail` INTEGER,
    `overrideprice` INTEGER,
    `uom` VARCHAR(100),
    `created` TIMESTAMP,
    `customerid` INTEGER,
    `salesperson` INTEGER,
     FOREIGN KEY(salesperson) REFERENCES users(username)
);

CREATE INDEX index_pricing_override_report_sku
ON pricing_override_report (sku);

CREATE INDEX index_pricing_override_report_customer_id
ON pricing_override_report (customerid);

CREATE TABLE `item_table_checkbox` (
    `version` integer,
    `sku` VARCHAR(25),
    `checked` BOOLEAN DEFAULT 0,
    `customerid` INTEGER,
    `salesperson` VARCHAR(100),
    `created` TIMESTAMP,
     FOREIGN KEY(salesperson) REFERENCES users(username),
     PRIMARY KEY(sku, customerid, salesperson)
);