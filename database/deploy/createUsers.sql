START TRANSACTION;

CREATE TABLE concrete.users (
    id uuid NOT NULL,

    "emailAddress" CHARACTER VARYING NOT NULL,
    "passwordHash" CHARACTER VARYING NOT NULL,

    CONSTRAINT "usersPk"
        PRIMARY KEY (id)
);

CREATE UNIQUE INDEX "usersEmailAddressIx"
    ON concrete.users
    ("emailAddress");

GRANT SELECT, INSERT
    ON TABLE concrete.users
    TO "concreteSystem";

COMMIT WORK;
