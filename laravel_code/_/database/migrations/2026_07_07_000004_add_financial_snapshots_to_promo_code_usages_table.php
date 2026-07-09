<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('promo_code_usages')) {
            return;
        }

        Schema::table('promo_code_usages', function (Blueprint $table) {
            if (! Schema::hasColumn('promo_code_usages', 'gateway_fee_amount')) {
                $table->decimal('gateway_fee_amount', 12, 2)->default(0)->after('platform_commission_amount');
            }

            if (! Schema::hasColumn('promo_code_usages', 'final_paid_amount')) {
                $table->decimal('final_paid_amount', 12, 2)->default(0)->after('gateway_fee_amount');
            }

            if (! Schema::hasColumn('promo_code_usages', 'creator_net_amount')) {
                $table->decimal('creator_net_amount', 12, 2)->default(0)->after('final_paid_amount');
            }

            if (! Schema::hasColumn('promo_code_usages', 'admin_net_amount')) {
                $table->decimal('admin_net_amount', 12, 2)->default(0)->after('creator_net_amount');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('promo_code_usages')) {
            return;
        }

        Schema::table('promo_code_usages', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['gateway_fee_amount', 'final_paid_amount', 'creator_net_amount', 'admin_net_amount'] as $column) {
                if (Schema::hasColumn('promo_code_usages', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if ($dropColumns) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
