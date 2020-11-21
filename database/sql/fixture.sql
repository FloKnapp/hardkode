INSERT INTO hk_user (`name`, `email`, `password`) VALUES ('Faulancer', 'office@florianknapp.de', 'test');

INSERT INTO hk_role (`name`) VALUES ('anonymous');
INSERT INTO hk_role (`name`) VALUES ('registered');
INSERT INTO hk_role (`name`) VALUES ('moderator');
INSERT INTO hk_role (`name`) VALUES ('admin');

INSERT INTO hk_user_role (`user_id`, `role_id`) VALUES (1, 4);

INSERT INTO hk_article (`user_id`, `title`, `teaser`, `content`) VALUES (1, 'Das ist ein Testartikel', 'Das ist der Teaser des Testartikels.', 'Dies ist der Conent des Testartikels. lol.');
INSERT INTO hk_article (`user_id`, `title`, `teaser`, `content`) VALUES (1, 'Das ist der zweite Testartikel', 'Das ist der Teaser des Testartikels.', 'Dies ist der Conent des Testartikels. lol.');
INSERT INTO hk_article (`user_id`, `title`, `teaser`, `content`) VALUES (1, 'Das ist der dritte Testartikel', 'Das ist der Teaser des Testartikels.', 'Dies ist der Conent des Testartikels. lol.');
INSERT INTO hk_article (`user_id`, `title`, `teaser`, `content`) VALUES (1, 'Das ist der vierte Testartikel', 'Das ist der Teaser des Testartikels.', 'Dies ist der Conent des Testartikels. lol.');