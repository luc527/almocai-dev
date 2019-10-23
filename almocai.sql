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
    on delete cascade
    on update cascade
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
    (date_add(new.data_inicio, interval 3 day), new.codigo, 'Quinta-feira');
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

create table if not exists Frequencia (
	# se o usuário geralmente almoça no IF ou nunca almoça
    # usado para determinar, automaticamente, a presença do aluno 
    codigo int primary key,
    descricao varchar(100)
);
insert into Frequencia (codigo, descricao) values (1, 'Sempre'), (2, 'Geralmente'), (3, 'Pouca vezes'), (4, 'Nunca');

create table if not exists Alimentacao (
	codigo int primary key auto_increment,
    descricao varchar(45)
);

insert into Alimentacao (descricao) values ('Come Carne'), ('Vegetariano'), ('Vegano');


create table if not exists Usuario (
	codigo int auto_increment primary key,
    username varchar(100) unique,
	senha varchar(255),
	nome varchar(100),
	tipo varchar(50),
    email varchar(255),
    
    alimentacao int default 1,
    foreign key (alimentacao) references Alimentacao(codigo) 
    on delete set null
    on update set null,
    
    frequencia int default 1,
    foreign key (frequencia) references Frequencia(codigo)  
    on delete set null
    on update set null
);

create table if not exists Carne (
	codigo int primary key auto_increment,
    descricao varchar(45)
);
insert into Carne (codigo, descricao) values (1, 'Frango'), (2, 'Porco'), (3, 'Boi');

create table if not exists Carne_usuario (
	usuario_cod int,
    carne_cod int,
    
    primary key (usuario_cod, carne_cod),
    
    foreign key (usuario_cod) references Usuario(codigo) on delete cascade,
    foreign key (carne_cod) references Carne(codigo) on delete cascade
);

insert into Usuario (username, senha, nome, tipo) values
('admin','d033e22ae348aeb5660fc2140aec35850c4da997','admin','ADMINISTRADOR');
-- senha (provisória): admin

create table if not exists Presenca (
	usuario_cod int,
	diaAlmoco_codigo int,
	presenca tinyint,
	primary key (usuario_cod, diaAlmoco_codigo),

	foreign key (usuario_cod) references Usuario(codigo)
		on delete cascade
		on update cascade,
  foreign key (diaAlmoco_codigo) references DiaAlmoco(codigo)
		on delete cascade
		on update cascade
);

create table if not exists Intolerancia (
	codigo int primary key auto_increment,
	descricao varchar(150)
);

create table if not exists Usuario_intolerancia (
	usuario_cod int,
	intolerancia_codigo int,
    arquivo varchar(2000),
	primary key (usuario_cod, intolerancia_codigo),
	foreign key (usuario_cod) references Usuario(codigo)
		on delete cascade,
	foreign key (intolerancia_codigo) references Intolerancia(codigo)
		on delete cascade
);

delimiter :)
create trigger AdicionaPresenca
after insert on DiaAlmoco 
for each row
begin
	declare idFrequencia int;
    declare tipoUsuario varchar(40);
    declare finished int default 0;
	declare id int;
	declare usuarioCursor cursor for select codigo from Usuario;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    
    open usuarioCursor;
    
    add_presenca : loop fetch usuarioCursor into id;
    if finished = 1 then
      leave add_presenca;
    end if;

    select frequencia into idFrequencia from Usuario where codigo = id;
    select tipo into tipoUsuario from Usuario where codigo = id;
    if tipoUsuario = 'Aluno' then
		if idFrequencia = 1 or idFrequencia = 2 then
			insert into Presenca value(id, new.codigo, 1);
		else
			insert into Presenca value(id, new.codigo, 0);
		end if;
    end if;
  end loop;

  close usuarioCursor;
end :)
delimiter ;
/* create view Semana as Select s.data_inicio, d.diaSemana, a.descricao, a.tipo from SemanaCardapio s, DiaAlmoco d,
 Alimento a where s.codigo = d.semanaCardapio_codigo and d.codigo = a.diaAlmoco_codigo; */