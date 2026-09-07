<?php
$files = glob('database/migrations/2026_09_06_1711*.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    if (strpos($f, 'principals') !== false || strpos($f, 'departments') !== false || strpos($f, 'positions') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->string(\'code\')->unique();'."\n".'            $table->string(\'name\');'."\n".'            $table->boolean(\'is_active\')->default(true);', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }
    if (strpos($f, 'employment_histories') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'user_id\')->constrained(\'users\');'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\');'."\n".'            $table->foreignId(\'position_id\')->constrained(\'positions\');'."\n".'            $table->foreignId(\'department_id\')->nullable()->constrained(\'departments\');'."\n".'            $table->string(\'nip\');'."\n".'            $table->date(\'start_date\');'."\n".'            $table->date(\'end_date\')->nullable();'."\n".'            $table->enum(\'status\', [\'ACTIVE\', \'INACTIVE\'])->default(\'ACTIVE\');'."\n".'            $table->enum(\'source\', [\'ODOO\', \'MANUAL\'])->default(\'MANUAL\');', '$table->timestamps();'], $c);
    }
    if (strpos($f, 'principal_histories') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'user_id\')->constrained(\'users\');'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\');'."\n".'            $table->date(\'start_date\');'."\n".'            $table->date(\'end_date\')->nullable();'."\n".'            $table->boolean(\'is_active\')->default(true);', '$table->timestamps();'], $c);
    }
    file_put_contents($f, $c);
}

$uf = glob('database/migrations/*_add_nik_and_phone_to_users_table.php')[0];
$uc = file_get_contents($uf);
$uc = str_replace('public function up(): void
    {
        Schema::table(\'users\', function (Blueprint $table) {
            //
        });
    }', 'public function up(): void
    {
        Schema::table(\'users\', function (Blueprint $table) {
            $table->string(\'nik\')->unique()->after(\'id\');
            $table->string(\'phone\')->nullable()->after(\'email\');
            $table->string(\'status\')->default(\'ACTIVE\')->after(\'password\');
            $table->softDeletes();
        });
    }', $uc);
file_put_contents($uf, $uc);
