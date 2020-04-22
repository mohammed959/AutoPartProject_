drop database if exists `AutoParts`;
create database if not exists `AutoParts`;
use `AutoParts`;
create table seller (
  seller_id         int          not null auto_increment,
  sname              varchar(255) not null,
  email             varchar(255) not null unique,
  password          varchar(512) not null,
  location          varchar(255) not null,
  certification_url varchar(255) not null,
  registration_time datetime     not null,
  status            int          not null,
  salt varchar(255) not null ,
  primary key (seller_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table user (
  user_id           int          not null auto_increment,
  name              varchar(255) not null,
  email             varchar(255) not null unique,
  password          varchar(512) not null,
  registration_time datetime     not null,
  status            int          not null,
  salt varchar(255) not null ,
  token varchar (255)
  primary key (user_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table admin (
  admin_id     int          not null auto_increment,
  name         varchar(255) not null,
  email        varchar(255) not null unique,
  password     varchar(255) not null,
  joining_date datetime     not null,
  salt varchar(255) not null ,
  primary key (admin_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table manufacturer (
  manufacturer_id int          not null auto_increment,
  name            varchar(255) not null,
  logo_url        varchar(255) not null,
  primary key (manufacturer_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table brand (
  brand_id        int primary key not null auto_increment,
  manufacturer_id int             not null,
  bname            varchar(255),
  start_model     int not null,
  end_model       int,
  foreign key (manufacturer_id) references manufacturer (manufacturer_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table category (
  cat_id    int          not null auto_increment primary key,
  cname      varchar(255) not null,
  parent_id int                   default null,
  foreign key (parent_id) references `category` (`cat_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table part (
  part_id     int          not null auto_increment primary key,
  pname        varchar(255) not null,
  description text,
  price       decimal      not null,
  seller_id   int          not null,
  category_id int          not null,
  foreign key (category_id) references `category` (`cat_id`),
  foreign key (seller_id) references `seller` (`seller_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table part_picture (
  picture_id int          not null primary key auto_increment,
  part_id    int          not null,
  url        varchar(255) not null,
  foreign key (part_id) references `part` (`part_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table part_car (
  part_id  int not null,
  brand_id int not null,
  start_model    int not null,
  end_model    int not null,
  primary key (part_id, brand_id, start_model, end_model),
  foreign key (brand_id) references `brand` (`brand_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table review (
  part_id int      not null,
  user_id int      not null,
  rating  int      not null,
  comment text     not null,
  ctime   datetime not null,
  primary key (part_id, user_id),
  foreign key (`part_id`) references `part` (`part_id`),
  foreign key (`user_id`) references `user` (`user_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table orders (
  order_id  int      not null auto_increment primary key,
  user_id   int      not null,
  seller_id int      not null,
  otime     datetime not null,
  status    int      not null,
  foreign key (user_id) references user (user_id),
  foreign key (seller_id) references seller (seller_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table order_item (
  i        int not null,
  order_id int not null,
  part_id  int not null,
  quantity int not null,
  primary key (i, order_id),
  foreign key (part_id) references part (part_id),
  foreign key (order_id) references orders (order_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
