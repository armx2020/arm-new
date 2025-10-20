<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index();
            $table->string('name_ru', 255)->index();
            $table->string('name_en', 255);
            $table->string('name_ru_locative', 255);
            $table->string('name_dat', 255)->nullable()->index();
            $table->foreignId('country_id')->index()->default(190);
            $table->string('transcription', 255);
            $table->integer('population')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 11, 6)->nullable();
        });

        $values = [
            1 => 146150789, 2 => 454744, 3 => 218866, 4 => 2332813, 5 => 793194, 6 => 1100290, 7 => 1014065, 8 => 4051005, 9 => 1547418, 10 => 1200187,
            11 => 983273, 12 => 1365805, 13 => 2507509, 14 => 1167713, 15 => 2327821, 16 => 3086126, 17 => 159913, 18 => 1065785, 19 => 1004180, 20 => 497393,
            21 => 2397763, 22 => 866219, 23 => 1002187, 24 => 272647, 25 => 1009380, 26 => 314723, 27 => 465563, 28 => 618056, 29 => 2674256, 30 => 1272109,
            31 => 830235, 32 => 637267, 33 => 5648235, 34 => 2874026, 35 => 834701, 36 => 1107041, 37 => 1847867, 38 => 1144035, 39 => 141234, 40 => 680380,
            41 => 795504, 42 => 12615279, 43 => 7599647, 44 => 748056, 45 => 43829, 46 => 3214623, 47 => 600296, 48 => 2793384, 49 => 1944195, 50 => 1963007,
            51 => 739467, 52 => 1318103, 53 => 2610800, 54 => 1902718, 55 => 629651, 56 => 4202320, 57 => 1114137, 58 => 3183038, 59 => 5383890, 60 => 2440815,
            61 => 967009, 62 => 489638, 63 => 4315699, 64 => 699253, 65 => 942363, 66 => 2795243, 67 => 1015966, 68 => 3898628, 69 => 1269636, 70 => 1077442,
            71 => 1478818, 72 => 324423, 73 => 1518695, 74 => 1507390, 75 => 1238416, 76 => 1321473, 77 => 536167, 78 => 1663795, 79 => 3475753, 80 => 1456951,
            81 => 1223395, 82 => 49663, 83 => 541479, 84 => 1259612, 85 => 1911818, 86 => 443212
        ];

        if (!empty($values)) {
            $sql = "UPDATE regions SET population = CASE ";

            foreach ($values as $id => $value) {
                $sql .= "WHEN id = $id THEN $value ";
            }

            $sql .= "END WHERE id IN (" . implode(',', array_keys($values)) . ")";

            DB::statement($sql);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
