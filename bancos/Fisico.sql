/* Lógico: */

CREATE TABLE users (
    name VARCHAR,
    email VARCHAR,
    password VARCHAR,
    remember BOOLEAN,
    id INTEGER PRIMARY KEY,
    phone VARCHAR,
    photo VARCHAR
);

CREATE TABLE pets (
    id INTEGER PRIMARY KEY,
    name VARCHAR,
    species VARCHAR,
    sex CHARACTER,
    breed VARCHAR,
    size VARCHAR,
    predominant_color VARCHAR,
    secondary_color VARCHAR,
    physical_details VARCHAR,
    date_disappearance TIMESTAMP,
    photo VARCHAR,
    fk_users_id INTEGER,
    fk_status_id INTEGER
);

CREATE TABLE status (
    id INTEGER PRIMARY KEY,
    name VARCHAR
);

CREATE TABLE sighted (
    fk_users_id INTEGER,
    fk_pets_id INTEGER,
    last_seen VARCHAR,
    id INTEGER PRIMARY KEY,
    data_sighted TIMESTAMP
);
 
ALTER TABLE pets ADD CONSTRAINT FK_pets_2
    FOREIGN KEY (fk_users_id)
    REFERENCES users (id)
    ON DELETE SET NULL;
 
ALTER TABLE pets ADD CONSTRAINT FK_pets_3
    FOREIGN KEY (fk_status_id)
    REFERENCES status (id)
    ON DELETE CASCADE;
 
ALTER TABLE sighted ADD CONSTRAINT FK_sighted_2
    FOREIGN KEY (fk_users_id)
    REFERENCES users (id)
    ON DELETE SET NULL;
 
ALTER TABLE sighted ADD CONSTRAINT FK_sighted_3
    FOREIGN KEY (fk_pets_id)
    REFERENCES pets (id)
    ON DELETE SET NULL;