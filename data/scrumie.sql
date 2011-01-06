CREATE TABLE "sprint" ("id_sprint" INTEGER PRIMARY KEY  NOT NULL ,"name" VARCHAR,"startdate" DATETIME, "estimation" INTEGER);
CREATE TABLE "task" ("id_task" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "body" TEXT NOT NULL  check(typeof("body") = 'text') , "estimation" INTEGER NOT NULL , "owner" VARCHAR, "id_sprint" INTEGER NOT NULL , "state" VARCHAR, "done" INTEGER NOT NULL  DEFAULT 0);
CREATE TABLE "task_history" ("id" integer PRIMARY KEY  NOT NULL  UNIQUE , "id_task" integer NOT NULL , "date" datetime NOT NULL , "done" integer NOT NULL );
CREATE TABLE "user" ("id_user" INTEGER PRIMARY KEY  NOT NULL ,"login" VARCHAR NOT NULL ,"password" VARCHAR NOT NULL );
