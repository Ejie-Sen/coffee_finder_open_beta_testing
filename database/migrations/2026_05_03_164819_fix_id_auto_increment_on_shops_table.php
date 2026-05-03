<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Force MySQL to drop its shields
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Attempt to drop constraints, but swallow the error if they are already gone
        $constraints = [
            'menu_items' => 'menu_items_ibfk_1',
            'shop_stats' => 'shop_stats_ibfk_1',
            'shop_vibes' => 'shop_vibes_ibfk_1'
        ];

        foreach ($constraints as $table => $key) {
            try {
                DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$key};");
            } catch (\Exception $e) {
                // Constraint was already dropped in a previous partial execution. Move on.
            }
        }

        // 3. Upgrade the entire database architecture to BIGINT UNSIGNED
        DB::statement('ALTER TABLE shops MODIFY id BIGINT UNSIGNED AUTO_INCREMENT;');
        DB::statement('ALTER TABLE menu_items MODIFY shop_id BIGINT UNSIGNED;');
        DB::statement('ALTER TABLE shop_stats MODIFY shop_id BIGINT UNSIGNED;');
        DB::statement('ALTER TABLE shop_vibes MODIFY shop_id BIGINT UNSIGNED;');

        // 4. Rebuild all the constraints securely using the new matching data types
        DB::statement('ALTER TABLE menu_items ADD CONSTRAINT menu_items_ibfk_1 FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE;');
        DB::statement('ALTER TABLE shop_stats ADD CONSTRAINT shop_stats_ibfk_1 FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE;');
        DB::statement('ALTER TABLE shop_vibes ADD CONSTRAINT shop_vibes_ibfk_1 FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE;');

        // 5. Turn shields back on
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
