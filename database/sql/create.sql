/* Article */

CREATE TABLE hk_article
(
    id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    teaser TEXT,
    content TEXT NOT NULL,
    category INTEGER DEFAULT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_by INTEGER,
    CONSTRAINT hk_article_pk
        PRIMARY KEY (id AUTOINCREMENT )
);

CREATE UNIQUE INDEX article_id_unique
    ON hk_article (id);

CREATE UNIQUE INDEX article_title_unique
    ON hk_article (title COLLATE NOCASE);

/* Category */

CREATE TABLE hk_category
(
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT hk_category_pk
        PRIMARY KEY (id AUTOINCREMENT )
);

CREATE UNIQUE INDEX category_id_unique
    ON hk_category (id);

CREATE UNIQUE INDEX category_title_unique
    ON hk_category (name COLLATE NOCASE);

/* Comment */

CREATE TABLE hk_comment
(
    id INTEGER NOT NULL,
    article_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_by INTEGER,
    CONSTRAINT hk_comment_pk
        PRIMARY KEY (id AUTOINCREMENT )
);

CREATE UNIQUE INDEX comment_id_unique
    ON hk_comment (id);

/* Role */

CREATE TABLE hk_role
(
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT hk_role_pk
        PRIMARY KEY (id AUTOINCREMENT )
);

CREATE UNIQUE INDEX role_id_unique
    ON hk_role (id);

CREATE UNIQUE INDEX role_name_unique
    ON hk_role (name COLLATE NOCASE);

/* User */

CREATE TABLE hk_user
(
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_by INTEGER,
    CONSTRAINT hk_user_pk
        PRIMARY KEY (id AUTOINCREMENT )
);

CREATE UNIQUE INDEX user_id_unique
    ON hk_user (id);

CREATE UNIQUE INDEX user_name_unique
    ON hk_user (name COLLATE NOCASE);

CREATE UNIQUE INDEX user_email_unique
    ON hk_user (email COLLATE NOCASE);

/* UserRole */

CREATE TABLE hk_user_role
(
    id INTEGER NOT NULL,
    user_id TEXT NOT NULL,
    role_id TEXT NOT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT hk_user_role_pk
        PRIMARY KEY (id AUTOINCREMENT )
);

CREATE UNIQUE INDEX user_role_id_unique
    ON hk_user_role (id);

CREATE UNIQUE INDEX user_role_user_role_unique
    ON hk_user_role (user_id, role_id);