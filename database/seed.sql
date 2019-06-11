-- Seed the database with data that tests expect there to be. This data is also
-- generally useful for playing around in a test environment.

INSERT INTO concrete.users
    (id, "emailAddress", "passwordHash")
VALUES
    ( '00000000-0000-0000-0000-000000000001'
    , 'john@example.com'
    , /* f00b4r */ '$2y$10$.A8l8EQD0ww8HkPkQIhzV.HWSjo/qhHXtbYfYx.YvkjF.JC4omcCS' );
