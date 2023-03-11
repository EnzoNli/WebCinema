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
    date_sortie date 
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

create view NbNotes as 
    select count(note) as nb, api_movie_id
    from Noter
    group by api_movie_id
;

insert into Film 
values (315162, "Le chat potté", date(2022-12-12));

insert into Noter
values ("Zoze", 315162, 2, "a fait peur à Kaloo"),
    ("Enzo", 315162, 3, "Olak a adoré"),
    ("TotorLeCastor", 315162, 5, "j'aime bien pcq y avait un chat");