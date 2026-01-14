<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            新しいクイズを作成
        </h2>
        <a href="{{ route('quizzes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
            &larr; 一覧に戻る
        </a>
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form action="{{ route('quizzes.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="category" value="カテゴリ" />
                        <select id="category" name="category" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Laravel">Laravel</option>
                            <option value="Docker">Docker</option>
                            <option value="Tailwind CSS">Tailwind CSS</option>
                            <option value="JavaScript">JavaScript</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="question" value="問題文" />
                        <textarea id="question" name="question" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="問題文を入力してください。改行も可能です。">{{ old('question') }}</textarea>
                        <x-input-error :messages="$errors->get('question')" class="mt-2" />
                    </div>

                    <div class="space-y-4">
                        <x-input-label value="選択肢（チェックを入れたものが正解になります）" />
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="flex items-center space-x-4">
                            <input type="radio" name="correct_index" value="{{ $i }}" {{ $i == 1 ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                            <x-text-input class="block w-full" type="text" name="choices[]" placeholder="選択肢 {{ $i }}" required />
                        </div>
                        @endfor
                        <x-input-error :messages="$errors->get('choices')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="explanation" value="解説（任意）" />
                        <textarea id="explanation" name="explanation" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('explanation') }}</textarea>
                        <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('quizzes.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900 mr-4">
                            キャンセル
                        </a>
                        <x-primary-button>
                            クイズを保存する
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>