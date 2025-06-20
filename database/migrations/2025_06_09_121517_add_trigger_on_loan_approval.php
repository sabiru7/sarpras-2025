<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddTriggerOnLoanApproval extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_loan_approved
            AFTER UPDATE ON loans
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
                    UPDATE items
                    SET quantity = quantity - NEW.quantity,
                        status = 'borrowed'
                    WHERE id = NEW.item_id;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_loan_approved");
    }
}
