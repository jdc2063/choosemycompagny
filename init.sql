CREATE DATABASE cmc_db;
USE `cmc_db`;

CREATE TABLE `job` (
    `id` int NOT NULL auto_increment,
    `reference` varchar(255),
    `title` varchar(255),
    `description` TEXT,
    `url` varchar(255),
    `company_name` varchar(255),
    `publication` date,
    PRIMARY KEY(`id`)
);


CREATE TABLE `partner` (
   `id` int NOT NULL auto_increment,
   `name` varchar(255),
   `file` varchar(255),
   PRIMARY KEY(`id`)
);

CREATE TABLE `nomenclature` (
    `id` int NOT NULL auto_increment,
    `reference` varchar(255),
    `title` varchar(255),
    `description` varchar(255),
    `prefix_url` varchar(255),
    `url` varchar(255),
    `company_name` varchar(255),
    `publication` varchar(255),
    `id_partenaire` int,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`id_partenaire`) REFERENCES partner(`id`)
);

INSERT INTO partner (name, file)
    values
    ('Regionsjob', 'regionsjob.xml'),
    ('JobTeaser', 'jobteaser.json');

INSERT INTO nomenclature (reference, title, description, prefix_url, url, company_name, publication, id_partenaire)
values
    ('ref', 'title','description',null,'url','company','pubDate', 1),
    ('reference', 'title','description','offerUrlPrefix','urlPath','companyname','publishedDate', 2);
