<?php
declare (strict_types = 1);
namespace concrete\users;

use concrete\utility\Postgresql;

/**
 * Authenticate a user given its credentials. If valid credentials are given,
 * the identifier of the user is returned.
 */
final
class Authenticate
{
    private
    /** @var Postgresql */ $db;

    function __construct(Postgresql $db)
    {
        $this->db = $db;
    }

    function authenticate(string $emailAddress, string $password): ?string
    {
        $row = $this->db->queryRow('
            SELECT users.id, users."passwordHash"
            FROM concrete.users
            WHERE users."emailAddress" = $1
        ', [$emailAddress]);

        if ($row === NULL)
            return NULL;

        list($id, $passwordHash) = $row;
        assert ($id !== NULL);
        assert ($passwordHash !== NULL);

        if (\password_verify($password, $row[1]))
            return $id;
        else
            return NULL;
    }
}

/**
 * Authenticating with correct credentials succeeds.
 */
\unittest(function(): void {
    $db = new Postgresql('');
    $authenticate = new Authenticate($db);

    $emailAddress = 'john@example.com';
    $password = 'f00b4r';
    $id = '00000000-0000-0000-0000-000000000001';

    $userId = $authenticate->authenticate($emailAddress, $password);
    assert($userId === $id);
});

/**
 * Authenticating with an invalid email address fails.
 */
\unittest(function(): void {
    $db = new Postgresql('');
    $authenticate = new Authenticate($db);

    $emailAddress = 'mary@example.com';
    $password = 'f00b4r';

    $userId = $authenticate->authenticate($emailAddress, $password);
    assert($userId === NULL);
});

/**
 * Authenticating with an invalid password fails.
 */
\unittest(function(): void {
    $db = new Postgresql('');
    $authenticate = new Authenticate($db);

    $emailAddress = 'john@example.com';
    $password = 'b42qux';

    $userId = $authenticate->authenticate($emailAddress, $password);
    assert($userId === NULL);
});
