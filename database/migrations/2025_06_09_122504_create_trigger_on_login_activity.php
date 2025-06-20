<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerOnLoginActivity extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_user_login_activity
            AFTER UPDATE ON users
            FOR EACH ROW
            BEGIN
                IF NEW.last_logined_at != OLD.last_logined_at THEN
                    INSERT INTO log_activities (user_id, action, log, ip_address, created_at, updated_at)
                    VALUES (
                        NEW.id,
                        'login',
                        CONCAT('User ', NEW.name, ' logged in via API'),
                        '', -- IP tidak tersedia dari trigger
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_user_login_activity");
    }
}
