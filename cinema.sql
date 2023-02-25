pragma foreign_keys = true;


-- view
-- triggers
-- transaction


-- entités
create table Utilisateur(
	login_  varchar(20)		check(length(login_)>=3)    primary key,
	mdp	    varchar(255)
);

create table Film(
    api_movie_id     integer     primary key,
    titre       varchar(50) not null,
    date_sortie date        not null
);

create table Acteur(
    api_acteur_id   integer     primary key,
    nom_acteur      varchar(50)     not null
);

create table Genre(
    api_genre_id    integer     primary key,
    nom_genre       varchar(50)     not null
);

-- associations
create table Noter(
    login_      varchar(20) references Utilisateur(login_),
    api_movie_id     integer     references Film(api_movie_id),     
    note        integer     check(note >=0 and note<=5)     not null,
    commentaire varchar(5000),
    constraint pkNoter primary key (login_, id_film)
);

create table Jouer(
    api_acteur_id   integer     references Acteur(api_acteur_id),
    api_movie_id     integer     references Film(api_movie_id),     
    constraint pkJouer primary key (api_acteur_id, api_acteur_id)
);

create table Appartenir(
    api_genre_id   integer     references Genre(api_genre_id),
    api_movie_id     integer     references Film(api_movie_id),     
    constraint pkJouer primary key (api_acteur_id, api_acteur_id)
);

-- views
create view NoteMoyenne as 
    select avg(note) as moyenne, api_movie_id
    from Noter
    group by api_movie_id
;

create view MeilleureNotes as 
    select api_movie_id, moyenne -- pour "écrire 4.7/5"
    from NoteMoyenne
    order by moyenne desc
;

create view NbNotes as 
    select count(note) as nb, api_movie_id
    from Noter
    group by api_movie_id
;

create view PopulariteDecroissant as
    select api_movie_id, nb -- pour écrire "30 avis"
    from NbNotes
    order by nb desc
;

create view PopulariteCroissant as
    select api_movie_id, nb
    from NbNotes
    order by nb asc
;

-- date de sortie

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

-- pour essayer d'inserer dasns noter, faut déjà avoir insérer dans film 
-- donc tout ça c'est une transaction

create trigger AjouteNote
after insert on Noter
begin 
    --- recalculer la moyenne
    update Film set moyenne_note = (select avg(note) 
                                   from Noter 
                                   where old.id_film = id_film) -- group by ?
                                where old.id_film = id_film;
end;
