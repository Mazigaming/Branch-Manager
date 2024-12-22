<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->timestamps();
        });

        $branches = DB::table('branches')->get();
        foreach ($branches as $branch) {
            DB::table('leaves')->insert([
                'branch_id' => $branch->id,
                'value' => $branch->leaf,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('leaf');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('leaf');
        });

        $leaves = DB::table('leaves')->get();
        foreach ($leaves as $leaf) {
            DB::table('branches')
                ->where('id', $leaf->branch_id)
                ->update(['leaf' => $leaf->value]);
        }

        Schema::dropIfExists('leaves');
    }
};
