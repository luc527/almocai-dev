create database almocai;
use almocai;

create table if not exists SemanaCardapio (
	codigo int primary key auto_increment,
    data_inicio date -- o primeiro dia (segunda) da semana, p. ex. 2019-08-12
);

create table if not exists DiaAlmoco (
	codigo int primary key auto_increment,
	`data` date,
	semanaCardapio_codigo int,
	diaSemana varchar(45),

	foreign key (semanaCardapio_codigo) references SemanaCardapio(codigo)
);

delimiter :)
create trigger cria_dias_semana
after insert on SemanaCardapio
for each row
begin
	insert into DiaAlmoco (`data`, semanaCardapio_codigo, diaSemana) values
    (new.data_inicio, new.codigo, 'Segunda-feira'),
    (date_add(new.data_inicio, interval 1 day), new.codigo, 'Terça-feira'),
    (date_add(new.data_inicio, interval 2 day), new.codigo, 'Quarta-feira'),
    (date_add(new.data_inicio, interval 3 day), new.codigo, 'Quinta-feira'),
    (date_add(new.data_inicio, interval 4 day), new.codigo, 'Sexta-feira');
end :)
delimiter ;

create table if not exists Alimento (
	codigo int primary key auto_increment,
	descricao varchar(100),
	diaAlmoco_codigo int,
	tipo varchar(45),
    
	foreign key (diaAlmoco_codigo) references DiaAlmoco(codigo)
		on delete cascade
		on update cascade
);

create table if not exists Usuario (
	matricula int primary key auto_increment,
	senha varchar(255),
	nome varchar(100),
	tipo varchar(50)
);
insert into Usuario (matricula, senha, nome, tipo) values
('2019','d033e22ae348aeb5660fc2140aec35850c4da997','admin','ADMINISTRADOR');
-- senha (provisória): admin

create table if not exists Presenca (
	usuario_matricula int,
	diaAlmoco_codigo int,
	presenca tinyint,
	primary key (usuario_matricula, diaAlmoco_codigo),

	foreign key (usuario_matricula) references Usuario(matricula)
		on delete cascade
		on update cascade,
  foreign key (diaAlmoco_codigo) references DiaAlmoco(codigo)
		on delete cascade
		on update cascade
);
