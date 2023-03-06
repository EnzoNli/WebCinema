pragma foreign_keys = true;
PRAGMA encoding="UTF-8";
PRAGMA case_sensitive_like = true;

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
    note        integer     check(note >=0 and note<=10)     not null,
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

create view NbNotes as 
    select count(note) as nb, api_movie_id
    from Noter
    group by api_movie_id
;

/*
BEGIN TRANSACTION;

INSERT into Film (api_movie_id, titre, date_sortie) 
select 666, "KLOU ET LES OVNI", date("2021-12-01")
WHERE NOT EXISTS (SELECT 1 FROM Film WHERE api_movie_id = 666);
        
INSERT INTO Genre (api_genre_id, nom_genre) 
SELECT 11,"spatiale"
WHERE NOT EXISTS (SELECT 1 FROM Genre WHERE api_genre_id = 11);

INSERT INTO Appartenir (api_genre_id, api_movie_id) 
select 11,666 
WHERE NOT EXISTS (SELECT 1 FROM Appartenir WHERE api_movie_id = 666 AND api_genre_id = 11);

INSERT INTO Acteur (api_acteur_id, nom_acteur) 
SELECT 3, "Olak" 
WHERE NOT EXISTS (SELECT 1 FROM Acteur WHERE api_acteur_id = 3);
      
INSERT INTO Jouer (api_acteur_id, api_movie_id) 
select 3, 666
WHERE NOT EXISTS (SELECT 1 FROM Jouer WHERE api_movie_id = 666 AND api_acteur_id = 3);
  
INSERT INTO Noter (login_, api_movie_id, note, commentaire) 
SELECT "Zoze", 666, 8, "Très bien"
WHERE NOT EXISTS (SELECT 1 FROM Noter WHERE api_movie_id = 666 AND login_ = "Zoze");

COMMIT;
*/


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

