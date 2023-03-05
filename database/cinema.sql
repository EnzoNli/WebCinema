pragma foreign_keys = true;
PRAGMA encoding="UTF-8";
PRAGMA case_sensitive_like = true;


-- view
-- triggers
-- transaction


-- entités
create table Utilisateur(
	login_  varchar(20)		check(length(login_)>=3)    primary key,
	mdp	    varchar(255)
);

create table Film(
    api_movie_id     integer    primary key,
    titre       varchar(50)     not null,
    date_sortie date     not null
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
    constraint pkNoter primary key (login_, api_movie_id)
);

create table Jouer(
    api_acteur_id   integer     references Acteur(api_acteur_id),
    api_movie_id     integer     references Film(api_movie_id),     
    constraint pkJouer primary key (api_acteur_id, api_movie_id)
);

create table Appartenir(
    api_genre_id   integer     references Genre(api_genre_id),
    api_movie_id     integer     references Film(api_movie_id),     
    constraint pkAppartenir primary key (api_genre_id, api_movie_id)
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

-- utile ?
create view PopulariteDecroissant as
    select api_movie_id, nb -- pour écrire "30 avis"
    from NbNotes
    order by nb desc
;

-- utile ?
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


create table Film(
    api_movie_id     integer    primary key,
    titre       varchar(50)     not null,
    date_sortie date     not null

BEGIN TRANSACTION;
    IF NOT EXISTS(SELECT api_movie_id FROM Film WHERE api_movie_id = :movie_key)
        BEGIN
            INSERT INTO Film (api_movie_id, titre, date_sortie) 
            VALUES (:movie_key, :titre_film, :date_sortie)

            -- pour chaque genre
            IF NOT EXISTS(SELECT api_genre_id FROM Genre WHERE api_genre_id = :genre_key)
                BEGIN
                    INSERT INTO Genre (api_genre_id, nom_genre) 
                    VALUES (:genre_key, :nom_genre)
                END

            INSERT INTO Appartenir (api_genre_id, api_movie_id)
            VALUES (:genre_key, :movie_key)
            -- fin pour chaque genre

            -- pour chaque acteur
            IF NOT EXISTS(SELECT api_acteur_id FROM Acteur WHERE api_acteur_id = :acteur_key)
                BEGIN
                    INSERT INTO Acteur (api_acteur_id, nom_acteur) 
                    VALUES (:acteur_key, :nom_acteur)
                END

            INSERT INTO Jouer (api_acteur_id, api_movie_id) 
            VALUES (:acteur_key,:movie_key)
            -- fin pour chaque acteur
        END

    -- insérer notes
    IF NOT EXISTS (SELECT * FROM Noter WHERE api_movie_id = :movie_key AND login_ = :login_)
        BEGIN 
            INSERT INTO Noter (login_, api_movie_id, note, commentaire)
            VALUES (:login_, :api_movie_id, :note, :commentaire)
        END

COMMIT;


INSERT INTO Genre (api_genre_id, nom_genre)
VALUES  (1, "Genre1"), 
        (2, "Genre2");

INSERT INTO Acteur (api_acteur_id, nom_acteur)
VALUES  (1, "EnzoLeDino"), 
        (2, "KlouLeDino");

INSERT INTO Film (api_movie_id, titre, date_sortie)
VALUES  (1, "star wars", date("2022-12-06")),
        (2, "pelouse", date("2022-01-02")),
        (3, "danse avec les stars !", date("2022-06-07"));

