CREATE TABLE hk_article
(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    teaser TEXT,
    content TEXT NOT NULL,
    inserted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_by INTEGER
);

CREATE UNIQUE INDEX article_id_unique
    ON hk_article (id);

CREATE UNIQUE INDEX article_title_unique
    ON hk_article (title collate nocase);