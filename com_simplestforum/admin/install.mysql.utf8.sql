CREATE TABLE IF NOT EXISTS `#__simplestforum_forum` (
    `id` int auto_increment not null,
    `name` varchar(100),
    `description` text,
    `ordering` int,
    `viewgid` int,
    `postgid` int,
    `moderategid` int,

    PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `#__simplestforum_post` (
    `id` int auto_increment not null,
    `subject` varchar(255),
    `message` text,
    `authorId` varchar(25),
    `parentId` int,
    `forumId` int,
    `thread` int,
    `date` datetime,
    `published` bool default false,
    `modified` datetime,
    `modified_by` int,

    PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `#__simplestforum_extension_attributes` (
    `id` int auto_increment not null,
    `extension` varchar(50),
    `aux1` int,
    `aux2` int,
    `aux3` int,
    `aux4` text,

    PRIMARY KEY(`id`)
);
