<?php
declare (strict_types = 1);
namespace concrete\utility;

/**
 * An instance of the Postgresql class contains the state for a connection to a
 * PostgreSQL database. The Postgresql class is a thin wrapper around the pgsql
 * extension, providing a convenient, efficient, type-safe interface.
 */
final
class Postgresql
{
    private
    /** @var resource */ $libpq;

    /**
     * Connect to a PostgreSQL database using the given connection string. See
     * the libpq documentation for more information about connection strings.
     */
    function __construct(string $connectionString)
    {
        $this->libpq = \pg_connect($connectionString);
    }

    /**
     * Execute an SQL query and yield each row in its result. The SQL query is
     * not executed until the first row is requested. The rows are arrays of
     * mixed nulls and strings. The strings represent the values of the
     * corresponding fields in the PostgreSQL text format. The arguments are
     * values that are to be substituted for parameters after the SQL query has
     * been parsed, and are likewise in the PostgreSQL text format. See the
     * PostgreSQL documentation for more information about the SQL syntax.
     *
     * @param array<int,?string> $arguments
     * @return iterable<int,array<?string>>
     */
    function query(string $query, array $arguments): iterable
    {
        $result = \pg_query_params($this->libpq, $query, $arguments);
        for (;;)
        {
            $row = \pg_fetch_row($result);
            if ($row === FALSE)
                break;
            yield $row;
        }
    }

    /**
     * Identical to query, but always execute the SQL query, and return only
     * the first row, or NULL if there are none.
     *
     * @param array<int,?string> $arguments
     * @return ?array<?string>
     */
    function queryRow(string $query, array $arguments): ?array
    {
        $result = \pg_query_params($this->libpq, $query, $arguments);
        $row = \pg_fetch_row($result);
        if ($row === FALSE)
            return NULL;
        return $row;
    }

    /**
     * Identical to queryRow, but do not return any rows.
     *
     * @param array<int,?string> $arguments
     */
    function execute(string $query, array $arguments): void
    {
        \pg_query_params($this->libpq, $query, $arguments);
    }
}

\unittest(function(): void {
    $db = new Postgresql('');
    $rows = $db->query(
        'VALUES (1, 2), ($1, NULL), (NULL, $2)',
        ['3', NULL]
    );
    foreach ($rows as $i => $row)
        switch ($i)
        {
            case 0: assert($row === ['1', '2']); break;
            case 1: assert($row === ['3', NULL]); break;
            case 2: assert($row === [NULL, NULL]); break;
        }
    assert(($i ?? NULL) === 2);
});

\unittest(function(): void {
    $db = new Postgresql('');
    $row = $db->queryRow('SELECT WHERE FALSE', []);
    assert($row === NULL);
});

\unittest(function(): void {
    $db = new Postgresql('');
    $row = $db->queryRow('SELECT 1, $1, $2', ['2', NULL]);
    assert($row === ['1', '2', NULL]);
});
