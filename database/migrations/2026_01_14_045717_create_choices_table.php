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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            // foreignId で quiz_id を作成し、quizが消えたら選択肢も消える(cascade)設定
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->string('choice_text'); // 選択肢の文言
            $table->boolean('is_correct')->default(false); // 正解かどうか
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
