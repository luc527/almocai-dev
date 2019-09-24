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
    codigo int primary key auto_increment,
    descricao varchar(100)
);
insert into Frequencia (codigo, descricao) values (1, 'PRESENCA'), (2, 'AUSENCIA');

create table if not exists Alimentacao (
	codigo int primary key auto_increment,
    descricao varchar(45)
);
insert into Alimentacao (codigo, descricao) values (1, 'Carnívoro'), (2, 'Vegetariano'), (3, 'Vegano');

create table if not exists Usuario (
	matricula int primary key,
	senha varchar(255),
	nome varchar(100),
	tipo varchar(50),
    
    alimentacao int default 1,
    foreign key (alimentacao) references Alimentacao(codigo),
    
    frequencia int,
    foreign key (frequencia) references Frequencia(codigo)
);

create table if not exists Carne (
	codigo int primary key auto_increment,
    descricao varchar(45)
);
insert into Carne (descricao) values ('Frango'), ('Porco'), ('Boi');

create table if not exists Carne_usuario (
	usuario_matricula int,
    carne_cod int,
    
    primary key (usuario_matricula, carne_cod),
    
    foreign key (usuario_matricula) references Usuario(matricula),
    foreign key (carne_cod) references Carne(codigo)
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

delimiter :)
create trigger AdicionaPresença
after insert on DiaAlmoco 
for each row
begin
	declare idFrequencia int;
    declare descricaoFrequencia varchar(40);
    declare finished int default 0;
	declare id int;
	declare usuarioCursor cursor for select matricula from Usuario;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    
    open usuarioCursor;
    
    add_presenca : loop fetch usuarioCursor into id;
    if finished = 1 then
      leave add_presenca;
    end if;

    select frequencia into idFrequencia from Usuario where matricula = id;
    select descricao into descricaoFrequencia from Frequencia where codigo = idFrequencia;
    
    if descricaoFrequencia = 'PRESENCA' then
		insert into Presenca value(id, new.codigo, 1);
	
    else
		insert into Presenca value(id, new.codigo, 0);
	end if;
  end loop;

  close usuarioCursor;
end :)
delimiter ;
