INSERT INTO settings (id, code, `value`, description, created_at, updated_at) VALUES (1,'tabTicketable','false','タブレット発番可否(営業時間内のみ発番可能))',NOW(),NOW());
INSERT INTO settings (id, code, `value`, description, created_at, updated_at) VALUES (2,'webTicketable','false','WEB発番可否(営業時間内のみ発番可能)',NOW(),NOW());
INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (1,'Akatsuki AH Staff','akatsuki-ah@hb.tp1.jp',null,'$2y$10$ELjDxFm28Tq9ZnZhWRPzle/Y7TtZfSReO91f7GFdQEAYv8B7dXvE2',null,CURDATE(),CURDATE());
