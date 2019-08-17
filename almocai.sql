create database almocai;
use almocai;

create table if not exists SemanaCardapio (
	codigo int primary key auto_increment,
    data_inicio date -- o primeiro dia (segunda) da semana, p. ex. 2019-08-12
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

delimiter :)
create trigger cria_dias_semana
after insert on SemanaCardapio 
for each row
begin
	insert into DiaAlmoco (`data`, semanaCardapio_codigo, diaSemana_codigo) values
    (new.data_inicio, new.codigo, 1),
    (date_add(new.data_inicio, interval 1 day), new.codigo, 2),
    (date_add(new.data_inicio, interval 2 day), new.codigo, 3),
    (date_add(new.data_inicio, interval 3 day), new.codigo, 4),
    (date_add(new.data_inicio, interval 4 day), new.codigo, 5);
end :)
delimiter ;

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