create database almocai;
use almocai;

create table Alimento (
	codigo int primary key auto_increment,
    descricao varchar(100)
    diaAlmoco_codigo int,
    foreign key (diaAlmoco_codigo) 
);

create table DiaAlmoco (
	codigo int primary key auto_increment,
    
)