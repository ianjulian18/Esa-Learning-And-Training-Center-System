<?php
$files = glob('database/migrations/*_create_courses_table.php');
if (count($files) > 0) {
    $f = $files[0];
    $c = file_get_contents($f);
    $c = str_replace('[\'NOT STARTED\', \'IN PROGRESS\', \'COMPLETED\', \'FAILED\', \'EXPIRED\', \'CANCELLED\', \'PRINCIPAL INACTIVE\'])->default(\'NOT STARTED\');', '[\'DRAFT\', \'PUBLISHED\', \'ARCHIVED\'])->default(\'DRAFT\');', $c);
    file_put_contents($f, $c);
}
