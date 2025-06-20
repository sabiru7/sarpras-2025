<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerOnReturnApproval extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_return_approved
            AFTER UPDATE ON return_requests
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
                    UPDATE items
                    SET quantity = quantity + (
                        SELECT quantity FROM loans WHERE id = NEW.loan_id
                    ),
                    status = 'available'
                    WHERE id = (
                        SELECT item_id FROM loans WHERE id = NEW.loan_id
                    );
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_return_approved");
    }
}
