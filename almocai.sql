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

create table if not exists Tipo_alimento (
	codigo int primary key auto_increment,
	descricao varchar(50)
);
insert into Tipo_alimento values
	(1, "Normal"),
	(2, "Carne"),
	(3, "Opção vegetariana"),
	(4, "Opção vegana");

create table if not exists Alimento (
	codigo int primary key auto_increment,
	descricao varchar(100),
	diaAlmoco_codigo int,
	tipo_cod int,

	foreign key (tipo_cod) references Tipo_alimento(codigo)
		on delete set null
		on update cascade,

	foreign key (diaAlmoco_codigo) references DiaAlmoco(codigo)
		on delete cascade
		on update cascade
);

create table if not exists Tipo_usuario (
	codigo int primary key auto_increment,
	descricao varchar(50)
);
insert into Tipo_usuario (codigo, descricao) values
	(1, "Administrador(a)"),
	(2, "Funcionário(a)"),
	(3, "Aluno(a)");

create table if not exists Usuario (
	matricula int primary key auto_increment,
	senha varchar(255),
	nome varchar(100),
	tipo_cod int,

	foreign key (tipo_cod) references Tipo_usuario(codigo)
		on update cascade
		on delete set null
);
insert into Usuario (matricula, senha, nome, tipo_cod) values
('2019','d033e22ae348aeb5660fc2140aec35850c4da997','admin',1);
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
