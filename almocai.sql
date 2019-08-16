create database almocai;
use almocai;

create table SemanaCardapio (
	codigo int primary key auto_increment
);

create table DiaAlmoco (
	codigo int primary key auto_increment,
    `data` date,
    semanaCardapio_codigo int,
    foreign key (semanaCardapio_codigo) references SemanaCardapio(codigo)
);

create table Alimento (
	codigo int primary key auto_increment,
    descricao varchar(100),
    diaAlmoco_codigo int,
    foreign key (diaAlmoco_codigo) references DiaAlmoco(codigo)
);

create table Aluno(
    matricula int primary key,
    senha varchar(40),
    nome varchar(100)
);