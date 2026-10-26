<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. mpdr_approved_details: add approver_position and change approved_date to dateTime
        if (Schema::hasTable('mpdr_approved_details')) {
            Schema::table('mpdr_approved_details', function (Blueprint $table) {
                if (!Schema::hasColumn('mpdr_approved_details', 'approver_position')) {
                    $table->string('approver_position')->nullable()->after('approver_name');
                }
            });

            // Change approved_date to DATETIME NULL
            DB::statement("ALTER TABLE `mpdr_approved_details` MODIFY `approved_date` DATETIME NULL;");

            // Backfill approver_position from users & positions
            try {
                DB::statement("
                    UPDATE `mpdr_approved_details` d
                    INNER JOIN `users` u ON d.approver_nik = u.nik
                    INNER JOIN `positions` p ON u.position_id = p.id
                    SET d.approver_position = p.name
                    WHERE d.approver_position IS NULL OR d.approver_position = '';
                ");
            } catch (\Throwable $e) {
                // Ignore if positions join fails
            }

            // Backfill approved_date time component from updated_at if approved_date was truncated to 00:00:00
            try {
                DB::statement("
                    UPDATE `mpdr_approved_details`
                    SET `approved_date` = `updated_at`
                    WHERE `approved_date` IS NOT NULL
                      AND TIME(`approved_date`) = '00:00:00'
                      AND `updated_at` IS NOT NULL;
                ");
            } catch (\Throwable $e) {
                // Ignore if update fails
            }
        }

        // 2. pre_mpdr_approved_details: change approved_date to dateTime & backfill position/datetime
        if (Schema::hasTable('pre_mpdr_approved_details')) {
            Schema::table('pre_mpdr_approved_details', function (Blueprint $table) {
                if (!Schema::hasColumn('pre_mpdr_approved_details', 'approver_position')) {
                    $table->string('approver_position')->nullable()->after('name');
                }
            });

            // Change approved_date to DATETIME NULL
            DB::statement("ALTER TABLE `pre_mpdr_approved_details` MODIFY `approved_date` DATETIME NULL;");

            // Backfill approver_position from users & positions
            try {
                DB::statement("
                    UPDATE `pre_mpdr_approved_details` d
                    INNER JOIN `users` u ON d.approver = u.nik
                    INNER JOIN `positions` p ON u.position_id = p.id
                    SET d.approver_position = p.name
                    WHERE d.approver_position IS NULL OR d.approver_position = '';
                ");
            } catch (\Throwable $e) {
                // Ignore if positions join fails
            }

            // Backfill approved_date time component from updated_at
            try {
                DB::statement("
                    UPDATE `pre_mpdr_approved_details`
                    SET `approved_date` = `updated_at`
                    WHERE `approved_date` IS NOT NULL
                      AND TIME(`approved_date`) = '00:00:00'
                      AND `updated_at` IS NOT NULL;
                ");
            } catch (\Throwable $e) {
                // Ignore if update fails
            }
        }

        // 3. mpdr_initiator_approved_details: change approved_date to dateTime
        if (Schema::hasTable('mpdr_initiator_approved_details')) {
            DB::statement("ALTER TABLE `mpdr_initiator_approved_details` MODIFY `approved_date` DATETIME NULL;");

            try {
                DB::statement("
                    UPDATE `mpdr_initiator_approved_details`
                    SET `approved_date` = `updated_at`
                    WHERE `approved_date` IS NOT NULL
                      AND TIME(`approved_date`) = '00:00:00'
                      AND `updated_at` IS NOT NULL;
                ");
            } catch (\Throwable $e) {
                // Ignore if update fails
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('mpdr_approved_details')) {
            DB::statement("ALTER TABLE `mpdr_approved_details` MODIFY `approved_date` DATE NULL;");
            Schema::table('mpdr_approved_details', function (Blueprint $table) {
                if (Schema::hasColumn('mpdr_approved_details', 'approver_position')) {
                    $table->dropColumn('approver_position');
                }
            });
        }

        if (Schema::hasTable('pre_mpdr_approved_details')) {
            DB::statement("ALTER TABLE `pre_mpdr_approved_details` MODIFY `approved_date` DATE NULL;");
        }

        if (Schema::hasTable('mpdr_initiator_approved_details')) {
            DB::statement("ALTER TABLE `mpdr_initiator_approved_details` MODIFY `approved_date` DATE NULL;");
        }
    }
};
