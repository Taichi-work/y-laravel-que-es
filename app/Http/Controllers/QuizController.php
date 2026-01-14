<?php

namespace App\Http\Controllers;

use Gemini\Laravel\Facades\Gemini;
use App\Models\Quiz;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    /**
     * クイズ一覧表示
     */
    public function index()
    {
        // クイズを新しい順に、選択肢(choices)も一緒に取得
        $quizzes = Quiz::with('choices')->latest()->get();
        return view('quizzes.index', compact('quizzes'));
    }

    /**
     * 新規作成画面表示
     */
    public function create()
    {
        return view('quizzes.create');
    }

    /**
     * 手動での保存処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'category' => 'required|string',
            'choices' => 'required|array|min:4',
            'choices.*' => 'required|string|max:255',
            'correct_index' => 'required|integer|min:1|max:4',
        ]);

        DB::transaction(function () use ($request) {
            $quiz = Quiz::create([
                'question' => $request->question,
                'category' => $request->category,
                'explanation' => $request->explanation,
            ]);

            foreach ($request->choices as $index => $choiceText) {
                $quiz->choices()->create([
                    'choice_text' => $choiceText,
                    'is_correct' => ($index + 1) == $request->correct_index,
                ]);
            }
        });

        return redirect()->route('quizzes.index')->with('status', 'クイズを作成しました！');
    }

    /**
     * 編集画面表示
     */
    public function edit(Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question' => 'required|string',
            'category' => 'required|string',
            'choices' => 'required|array|min:4',
            'choices.*' => 'required|string|max:255',
            'correct_index' => 'required|integer|min:1|max:4',
        ]);

        DB::transaction(function () use ($request, $quiz) {
            $quiz->update([
                'question' => $request->question,
                'category' => $request->category,
                'explanation' => $request->explanation,
            ]);

            // 既存の選択肢を削除して再作成
            $quiz->choices()->delete();
            foreach ($request->choices as $index => $choiceText) {
                $quiz->choices()->create([
                    'choice_text' => $choiceText,
                    'is_correct' => ($index + 1) == $request->correct_index,
                ]);
            }
        });

        return redirect()->route('quizzes.index')->with('status', 'クイズを更新しました！');
    }

    /**
     * 削除処理
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('status', 'クイズを削除しました。');
    }

    /**
     * Geminiを使ってクイズを自動生成する
     */
    public function generate(Request $request)
    {
        $category = $request->input('category', 'Laravel');

        // プロンプトをより厳格に
        $prompt = "Create one 4-choice quiz about '{$category}' in Japanese. 
        The output MUST be ONLY a JSON object and nothing else. Do not include markdown formatting.
        {
            \"question\": \"問題文\",
            \"choices\": [
                {\"text\": \"選択肢1\", \"is_correct\": true},
                {\"text\": \"選択肢2\", \"is_correct\": false},
                {\"text\": \"選択肢3\", \"is_correct\": false},
                {\"text\": \"選択肢4\", \"is_correct\": false}
            ],
            \"explanation\": \"解説文\"
        }";

        try {
            $client = app(\Gemini\Client::class);
        
            // ✅ ここを修正: gemini-2.5-flash に変更
            $result = $client->generativeModel('gemini-2.5-flash')->generateContent($prompt);
            
            $text = $result->text();

            // デバッグ: AIが何を返してきたかログに記録
            Log::info("Gemini Response: " . $text);

            // JSON部分（ { から } まで）を抽出
            if (preg_match('/\{.*\}/s', $text, $matches)) {
                $jsonString = $matches[0];
            } else {
                $jsonString = $text;
            }

            $data = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'AIの応答がJSON形式ではありませんでした。内容: ' . mb_substr($text, 0, 50));
            }

            DB::transaction(function () use ($data, $category) {
                $quiz = Quiz::create([
                    'question' => $data['question'],
                    'category' => $category,
                    'explanation' => $data['explanation'] ?? '',
                ]);

                foreach ($data['choices'] as $choiceData) {
                    $quiz->choices()->create([
                        'choice_text' => $choiceData['text'],
                        'is_correct' => $choiceData['is_correct'],
                    ]);
                }
            });

            return redirect()->route('quizzes.index')->with('status', "AIが「{$category}」のクイズを生成しました!");

        } catch (\Exception $e) {
            // エラー内容を画面に詳しく出す
            return back()->with('error', 'エラー詳細: ' . $e->getMessage());
        }
    }
}