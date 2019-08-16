create database almocai;
use almocai;

create table if not exists SemanaCardapio (
	codigo int primary key auto_increment
);

create table if not exists DiaSemana (
	codigo int primary key auto_increment,
    descricao varchar(45)
);
insert into DiaSemana (descricao) values ('Segunda'), ('Terça'), ('Quarta'), ('Quinta'), ('Sexta');

create table if not exists DiaAlmoco (
	codigo int primary key auto_increment,
    `data` date,
    semanaCardapio_codigo int,
    diaSemana_codigo int,
    foreign key (semanaCardapio_codigo) references SemanaCardapio(codigo),
    foreign key (diaSemana_codigo) references DiaSemana(codigo)
);

create table if not exists Alimento (
	codigo int primary key auto_increment,
    descricao varchar(100),
    diaAlmoco_codigo int,
    foreign key (diaAlmoco_codigo) references DiaAlmoco(codigo)
);

create table if not exists Aluno (
    matricula int primary key,
    senha varchar(40),
    nome varchar(100)
);