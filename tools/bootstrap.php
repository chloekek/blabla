<?php
declare (strict_types = 1);

// This file is contains that code that is first executed when the application
// or the tests start. No other code should come before this code.

// Initialize the autoloader, so that we can use classes without require_once.
// Classes are instead loaded lazily. See the documentation of Composer for more
// information.
require_once __DIR__ . '/../vendor/composer/autoload.php';

// Set up an error handler, so that errors (including notices and warnings) are
// instead reported through exceptions. This makes them much easier to work
// with.
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// The unittest function defines a unit test. Once all unit tests are defined, they may be
// executed with UnitTests::run().
final class UnitTests {
    static $unitTests = [];
    static function run(): void { foreach (self::$unitTests as $unitTest) $unitTest(); }
}
function unittest(callable $f): void { UnitTests::$unitTests[] = $f; }
