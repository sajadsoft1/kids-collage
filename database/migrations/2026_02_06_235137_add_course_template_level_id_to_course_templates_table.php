<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\CourseLevelEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('course_templates', function (Blueprint $table) {
            $table->foreignId('course_template_level_id')->nullable()->after('category_id')->constrained('course_template_levels')->nullOnDelete();
        });

        $enumToLevelId = $this->seedCourseTemplateLevels();

        foreach ($enumToLevelId as $enumValue => $levelId) {
            DB::table('course_templates')->where('level', $enumValue)->update(['course_template_level_id' => $levelId]);
        }

        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropIndex(['level']);
        });

        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('course_templates', function (Blueprint $table) {
            $table->index('course_template_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('course_templates', function (Blueprint $table) {
            $table->string('level')->default(CourseLevelEnum::BIGGINER->value)->after('category_id');
        });

        $levelIdToEnum = [
            1 => CourseLevelEnum::BIGGINER->value,
            2 => CourseLevelEnum::NORMAL->value,
            3 => CourseLevelEnum::ADVANCE->value,
            4 => CourseLevelEnum::INTERMEDIATE->value,
        ];

        foreach ($levelIdToEnum as $id => $enumValue) {
            DB::table('course_templates')->where('course_template_level_id', $id)->update(['level' => $enumValue]);
        }

        Schema::table('course_templates', function (Blueprint $table) {
            $table->index('level');
        });

        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropForeign(['course_template_level_id']);
        });

        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropColumn('course_template_level_id');
        });
    }

    /** @return array<string, int> */
    private function seedCourseTemplateLevels(): array
    {
        $order = [
            CourseLevelEnum::BIGGINER->value,
            CourseLevelEnum::NORMAL->value,
            CourseLevelEnum::ADVANCE->value,
            CourseLevelEnum::INTERMEDIATE->value,
        ];

        $map = [];
        $locales = config('app.supported_locales', ['fa', 'en']);

        foreach ($order as $enumValue) {
            $id = DB::table('course_template_levels')->insertGetId([
                'published' => BooleanEnum::ENABLE->value,
                'languages' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $map[$enumValue] = $id;

            $enum = CourseLevelEnum::from($enumValue);
            $title = $enum->title();

            foreach ($locales as $locale) {
                DB::table('translations')->insert([
                    'translatable_id' => $id,
                    'translatable_type' => 'App\Models\CourseTemplateLevel',
                    'key' => 'title',
                    'value' => $title,
                    'locale' => $locale,
                ]);
            }
        }

        return $map;
    }
};
