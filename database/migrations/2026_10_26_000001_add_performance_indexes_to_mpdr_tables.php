<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. mpdr_forms
        if (Schema::hasTable('mpdr_forms')) {
            Schema::table('mpdr_forms', function (Blueprint $table) {
                if (!Schema::hasIndex('mpdr_forms', 'mpdr_forms_no_index')) {
                    $table->index('no', 'mpdr_forms_no_index');
                }
                if (!Schema::hasIndex('mpdr_forms', 'mpdr_forms_user_id_index')) {
                    $table->index('user_id', 'mpdr_forms_user_id_index');
                }
                if (Schema::hasColumn('mpdr_forms', 'requester_id') && !Schema::hasIndex('mpdr_forms', 'mpdr_forms_requester_id_index')) {
                    $table->index('requester_id', 'mpdr_forms_requester_id_index');
                }
                if (Schema::hasColumn('mpdr_forms', 'prempdr_no') && !Schema::hasIndex('mpdr_forms', 'mpdr_forms_prempdr_no_index')) {
                    $table->index('prempdr_no', 'mpdr_forms_prempdr_no_index');
                }
                if (!Schema::hasIndex('mpdr_forms', 'mpdr_forms_status_index')) {
                    $table->index('status', 'mpdr_forms_status_index');
                }
                if (!Schema::hasIndex('mpdr_forms', 'mpdr_forms_created_at_index')) {
                    $table->index('created_at', 'mpdr_forms_created_at_index');
                }
            });
        }

        // 2. pre_mpdr_forms
        if (Schema::hasTable('pre_mpdr_forms')) {
            Schema::table('pre_mpdr_forms', function (Blueprint $table) {
                if (!Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_no_index')) {
                    $table->index('no', 'pre_mpdr_forms_no_index');
                }
                if (!Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_user_id_index')) {
                    $table->index('user_id', 'pre_mpdr_forms_user_id_index');
                }
                if (!Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_status_index')) {
                    $table->index('status', 'pre_mpdr_forms_status_index');
                }
                if (!Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_created_at_index')) {
                    $table->index('created_at', 'pre_mpdr_forms_created_at_index');
                }
            });
        }

        // 3. mpdr_approved_details
        if (Schema::hasTable('mpdr_approved_details')) {
            Schema::table('mpdr_approved_details', function (Blueprint $table) {
                if (!Schema::hasIndex('mpdr_approved_details', 'mpdr_approved_details_approver_nik_index')) {
                    $table->index('approver_nik', 'mpdr_approved_details_approver_nik_index');
                }
                if (!Schema::hasIndex('mpdr_approved_details', 'mpdr_approved_details_status_index')) {
                    $table->index('status', 'mpdr_approved_details_status_index');
                }
            });
        }

        // 4. pre_mpdr_approved_details
        if (Schema::hasTable('pre_mpdr_approved_details')) {
            Schema::table('pre_mpdr_approved_details', function (Blueprint $table) {
                if (!Schema::hasIndex('pre_mpdr_approved_details', 'pre_mpdr_approved_details_approver_index')) {
                    $table->index('approver', 'pre_mpdr_approved_details_approver_index');
                }
                if (!Schema::hasIndex('pre_mpdr_approved_details', 'pre_mpdr_approved_details_status_index')) {
                    $table->index('status', 'pre_mpdr_approved_details_status_index');
                }
            });
        }

        // 5. mpdr_initiator_approved_details
        if (Schema::hasTable('mpdr_initiator_approved_details')) {
            Schema::table('mpdr_initiator_approved_details', function (Blueprint $table) {
                if (!Schema::hasIndex('mpdr_initiator_approved_details', 'mpdr_initiator_details_nik_index')) {
                    $table->index('initiator_nik', 'mpdr_initiator_details_nik_index');
                }
                if (!Schema::hasIndex('mpdr_initiator_approved_details', 'mpdr_initiator_details_status_index')) {
                    $table->index('status', 'mpdr_initiator_details_status_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('mpdr_forms')) {
            Schema::table('mpdr_forms', function (Blueprint $table) {
                if (Schema::hasIndex('mpdr_forms', 'mpdr_forms_no_index')) {
                    $table->dropIndex('mpdr_forms_no_index');
                }
                if (Schema::hasIndex('mpdr_forms', 'mpdr_forms_user_id_index')) {
                    $table->dropIndex('mpdr_forms_user_id_index');
                }
                if (Schema::hasIndex('mpdr_forms', 'mpdr_forms_requester_id_index')) {
                    $table->dropIndex('mpdr_forms_requester_id_index');
                }
                if (Schema::hasIndex('mpdr_forms', 'mpdr_forms_prempdr_no_index')) {
                    $table->dropIndex('mpdr_forms_prempdr_no_index');
                }
                if (Schema::hasIndex('mpdr_forms', 'mpdr_forms_status_index')) {
                    $table->dropIndex('mpdr_forms_status_index');
                }
                if (Schema::hasIndex('mpdr_forms', 'mpdr_forms_created_at_index')) {
                    $table->dropIndex('mpdr_forms_created_at_index');
                }
            });
        }

        if (Schema::hasTable('pre_mpdr_forms')) {
            Schema::table('pre_mpdr_forms', function (Blueprint $table) {
                if (Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_no_index')) {
                    $table->dropIndex('pre_mpdr_forms_no_index');
                }
                if (Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_user_id_index')) {
                    $table->dropIndex('pre_mpdr_forms_user_id_index');
                }
                if (Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_status_index')) {
                    $table->dropIndex('pre_mpdr_forms_status_index');
                }
                if (Schema::hasIndex('pre_mpdr_forms', 'pre_mpdr_forms_created_at_index')) {
                    $table->dropIndex('pre_mpdr_forms_created_at_index');
                }
            });
        }

        if (Schema::hasTable('mpdr_approved_details')) {
            Schema::table('mpdr_approved_details', function (Blueprint $table) {
                if (Schema::hasIndex('mpdr_approved_details', 'mpdr_approved_details_approver_nik_index')) {
                    $table->dropIndex('mpdr_approved_details_approver_nik_index');
                }
                if (Schema::hasIndex('mpdr_approved_details', 'mpdr_approved_details_status_index')) {
                    $table->dropIndex('mpdr_approved_details_status_index');
                }
            });
        }

        if (Schema::hasTable('pre_mpdr_approved_details')) {
            Schema::table('pre_mpdr_approved_details', function (Blueprint $table) {
                if (Schema::hasIndex('pre_mpdr_approved_details', 'pre_mpdr_approved_details_approver_index')) {
                    $table->dropIndex('pre_mpdr_approved_details_approver_index');
                }
                if (Schema::hasIndex('pre_mpdr_approved_details', 'pre_mpdr_approved_details_status_index')) {
                    $table->dropIndex('pre_mpdr_approved_details_status_index');
                }
            });
        }

        if (Schema::hasTable('mpdr_initiator_approved_details')) {
            Schema::table('mpdr_initiator_approved_details', function (Blueprint $table) {
                if (Schema::hasIndex('mpdr_initiator_approved_details', 'mpdr_initiator_details_nik_index')) {
                    $table->dropIndex('mpdr_initiator_details_nik_index');
                }
                if (Schema::hasIndex('mpdr_initiator_approved_details', 'mpdr_initiator_details_status_index')) {
                    $table->dropIndex('mpdr_initiator_details_status_index');
                }
            });
        }
    }
};
