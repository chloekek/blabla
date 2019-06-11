START TRANSACTION;

CREATE SCHEMA concrete;

GRANT USAGE
    ON SCHEMA concrete
    TO "concreteSystem";

COMMIT WORK;
