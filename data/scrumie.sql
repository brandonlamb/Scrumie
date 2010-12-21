CREATE TABLE "sprint" ("id_sprint" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "name" VARCHAR UNIQUE );
CREATE TABLE sqlite_sequence(name,seq);
CREATE TABLE "task" ("id_task" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "body" TEXT NOT NULL  check(typeof("body") = 'text') , "estimation" INTEGER NOT NULL , "owner" VARCHAR, "id_sprint" INTEGER NOT NULL , "state" VARCHAR);
CREATE TABLE "user" ("id_user" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "email" VARCHAR NOT NULL  UNIQUE , "password" VARCHAR NOT NULL );
