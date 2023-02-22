pragma foreign_keys = true;


-- entités
-- associations
-- view
-- triggers
-- insertions

create table Utilisateur(
	login_  varchar(20)		check(length(login_)>=3)    primary key,
	mdp	    varchar(40)
);

create table Film(
    id_film     integer     primary key     autoincrement,
    titre       varchar(50) not null,
    date_sortie date        not null,
    moyenne_note        real         not null
    -- affiche     varchar(20)		default(null)
);

create table Noter(
    login_      varchar(20) references Utilisateur(login_),
    id_film     integer     references Film(id_film),     
    note        integer         check(note >=0 and note<=5),
    constraint pkNoter primary key (login_, id_film)
);

create view Film5Etoiles as
    select id_film
    from Film
    where moyenne_note == 5.0
;



/* create trigger EmpecheAjoutNote
before insert on Noter
begin 
    -- si le film existe dans la db
    -- si le film n'existe pas
    -- si le login a déjà donné une note
    -- ...
    select 
        case 
            when (new.id_film not in Film)
            then -- ajouter le film à la db

            when (  select login_, id_film
                    from Noter
                    where new.id_film=id_film and new.login_ = login_)
            then raise(abort, 'Vous avez déjà noté ce film !')
        end;
end; */

create trigger AjouteNote
after insert on Noter
begin 
    --- recalculer la moyenne
    update Film set moyenne_note = (select avg(note) 
                                   from Noter 
                                   where old.id_film = id_film) -- group by ?
                                where old.id_film = id_film;
end;