USE sluder;

-- Drop tables in order of foreign keys
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS user_information;
DROP TABLE IF EXISTS order_products;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS products;

-- Create all tables
CREATE TABLE users (
  username varchar(255) NOT NULL,
  user_password varchar(255) NOT NULL,
  is_admin boolean NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE user_information (
  username varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(12) NOT NULL,
  shipping_street varchar(255) NOT NULL,
  shipping_city varchar(255) NOT NULL,
  shipping_zipcode varchar(10) NOT NULL,
  CONSTRAINT FOREIGN KEY (username) references users (username)
);

CREATE TABLE orders (
  order_id int(5) NOT NULL,
  username varchar(255) NOT NULL,
  date_time DATETIME NOT NULL,
  PRIMARY KEY (order_id),
  CONSTRAINT FOREIGN KEY (username) references users (username)
);

CREATE TABLE order_products (
  order_id int(5) NOT NULL,
  product_id int(5) NOT NULL,
  quantity int(5) NOT NULL,
  PRIMARY KEY (order_id, product_id)
);

CREATE TABLE cart (
  username varchar(255) NOT NULL,
  product_id int(5) NOT NULL,
  quantity int(5) NOT NULL,
  PRIMARY KEY (username, product_id),
  CONSTRAINT FOREIGN KEY (username) references users (username)
);

CREATE TABLE products (
  uuid varchar(5) NOT NULL,
  name varchar(255) NOT NULL,
  type varchar(255) NOT NULL,
  manufacturer varchar(255) NOT NULL,
  price DECIMAL NOT NULL,
  screen_size DECIMAL NOT NULL,
  width DECIMAL NOT NULL,
  height DECIMAL NOT NULL,
  depth DECIMAL NOT NULL,
  stock int(5) NOT NULL,
  PRIMARY KEY (uuid)
);