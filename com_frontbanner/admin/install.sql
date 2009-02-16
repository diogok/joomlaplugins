drop table if exists #__frontbanner; 

create table #__frontbanner (
        id int(11) auto_increment ,
        link text,
        path varchar(220),
        active int(1),
        primary key(id)
) engine=MyIsam;
