drop table if exists #__headline;

create table #__headline (
        id int(11) auto_increment,
        content_id int(11) default 0,
        readmore int(11) default 0,
        maxsize int(11) default 150,
        primary key(id)
        );

insert into #__headline (content_id, readmore, maxsize) values ( 1, 1, 150);
