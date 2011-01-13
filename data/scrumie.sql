CREATE TABLE "project" ("id" INTEGER PRIMARY KEY  NOT NULL ,"name" VARCHAR NOT NULL ,"password" VARCHAR NOT NULL );
CREATE TABLE "sprint" ("id_sprint" INTEGER PRIMARY KEY  NOT NULL ,"name" VARCHAR,"startdate" DATETIME, "id_project" integer);
CREATE TABLE "task" ("id_task" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "body" TEXT NOT NULL  check(typeof("body") = 'text') , "estimation" INTEGER NOT NULL , "owner" VARCHAR, "id_sprint" INTEGER NOT NULL , "state" VARCHAR, "done" INTEGER NOT NULL  DEFAULT 0, "order" integer, "id_project" integer);
CREATE TABLE "task_history" ("id" integer PRIMARY KEY  NOT NULL  UNIQUE , "id_task" integer NOT NULL , "date" datetime NOT NULL , "done" integer NOT NULL );
