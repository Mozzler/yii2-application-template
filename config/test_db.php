<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mongodb://localhost:27017/new-project-test';

return $db;
