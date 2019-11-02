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
  PRIMARY KEY (username),
  CONSTRAINT FOREIGN KEY (username) references users (username)
);

CREATE TABLE products (
  uuid int(5) NOT NULL,
  name varchar(255) NOT NULL,
  product_type varchar(255) NOT NULL,
  manufacturer varchar(255) NOT NULL,
  price DECIMAL NOT NULL,
  screen_size DECIMAL NOT NULL,
  tv_width DECIMAL NOT NULL,
  tv_height DECIMAL NOT NULL,
  tv_depth DECIMAL NOT NULL,
  stock int(5) NOT NULL,
  PRIMARY KEY (uuid)
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
  PRIMARY KEY (order_id, product_id),
  CONSTRAINT FOREIGN KEY (order_id) references orders (order_id),
  CONSTRAINT FOREIGN KEY (product_id) references products (uuid)
);

CREATE TABLE cart (
  username varchar(255) NOT NULL,
  product_id int(5) NOT NULL,
  quantity int(5) NOT NULL,
  PRIMARY KEY (username, product_id),
  CONSTRAINT FOREIGN KEY (username) references users (username),
  CONSTRAINT FOREIGN KEY (product_id) references products (uuid)
);



-- Test data
INSERT INTO users (username, user_password, is_admin) VALUES ('sluder', 'password', 1);
INSERT INTO products (uuid, name, product_type, manufacturer, price, screen_size, tv_width, tv_height, tv_depth, stock) VALUES ('12345', 'Samsung HD', 'TV', 'Samsung', 80.00, 50, 60, 70, 5, 10);
INSERT INTO products (uuid, name, product_type, manufacturer, price, screen_size, tv_width, tv_height, tv_depth, stock) VALUES ('54321', 'Samsung FHD', 'TV', 'Samsung', 1100.00, 70, 800, 50, 5, 11);


INSERT INTO cart (username, product_id, quantity) VALUES ('sluder', '12345', 1);