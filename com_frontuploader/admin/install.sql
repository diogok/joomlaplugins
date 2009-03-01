drop table if exists #__frontuploader ;

create table #__frontuploader (
        id int(11) auto_increment,
        name text,
        path text,
        primary key(id)
        );
