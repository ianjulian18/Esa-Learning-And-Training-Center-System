<?php
$files = glob('database/migrations/2026_09_06_1742*.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    
    if (strpos($f, 'create_question_banks_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'principal_id\')->constrained(\'principals\')->cascadeOnDelete();'."\n".'            $table->string(\'title\');'."\n".'            $table->text(\'description\')->nullable();', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }
    
    if (strpos($f, 'create_questions_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'question_bank_id\')->nullable()->constrained(\'question_banks\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'course_id\')->nullable()->constrained(\'courses\')->cascadeOnDelete();'."\n".'            $table->enum(\'type\', [\'MULTIPLE_CHOICE\', \'TRUE_FALSE\', \'MULTIPLE_ANSWER\']);'."\n".'            $table->text(\'text\');'."\n".'            $table->json(\'options\')->nullable();'."\n".'            $table->json(\'correct_answers\');', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }

    if (strpos($f, 'create_assessments_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'course_id\')->constrained(\'courses\')->cascadeOnDelete();'."\n".'            $table->enum(\'type\', [\'PRE_TEST\', \'POST_TEST\']);'."\n".'            $table->string(\'title\');'."\n".'            $table->integer(\'passing_grade\')->default(80);'."\n".'            $table->integer(\'retake_limit\')->default(0);', '$table->timestamps();'."\n".'            $table->softDeletes();'], $c);
    }

    if (strpos($f, 'create_assessment_attempts_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'user_id\')->constrained(\'users\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'assessment_id\')->constrained(\'assessments\')->cascadeOnDelete();'."\n".'            $table->enum(\'status\', [\'IN PROGRESS\', \'PASSED\', \'FAILED\'])->default(\'IN PROGRESS\');'."\n".'            $table->integer(\'score\')->nullable();'."\n".'            $table->timestamp(\'started_at\')->useCurrent();'."\n".'            $table->timestamp(\'finished_at\')->nullable();', '$table->timestamps();'], $c);
    }

    if (strpos($f, 'create_assessment_answers_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->id();'."\n".'            $table->foreignId(\'attempt_id\')->constrained(\'assessment_attempts\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'question_id\')->constrained(\'questions\')->cascadeOnDelete();'."\n".'            $table->json(\'user_answer\')->nullable();'."\n".'            $table->boolean(\'is_correct\')->default(false);', '$table->timestamps();'], $c);
    }

    if (strpos($f, 'create_assessment_questions_table') !== false) {
        $c = str_replace(['$table->id();', '$table->timestamps();'], ['$table->foreignId(\'assessment_id\')->constrained(\'assessments\')->cascadeOnDelete();'."\n".'            $table->foreignId(\'question_id\')->constrained(\'questions\')->cascadeOnDelete();'."\n".'            $table->integer(\'order_number\')->default(0);', ''], $c);
    }

    file_put_contents($f, $c);
}
