<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                クイズを編集
            </h2>
            <a href="{{ route('quizzes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                &larr; 一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('quizzes.update', $quiz) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="category" value="カテゴリ" />
                        <select id="category" name="category" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Laravel', 'Docker', 'Tailwind CSS', 'JavaScript'] as $cat)
                                <option value="{{ $cat }}" {{ $quiz->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="question" value="問題文" />
                        <textarea id="question" name="question" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('question', $quiz->question) }}</textarea>
                    </div>

                    <div class="space-y-4">
                        <x-input-label value="選択肢（チェックを入れたものが正解になります）" />
                        @foreach ($quiz->choices as $index => $choice)
                        <div class="flex items-center space-x-4">
                            <input type="radio" name="correct_index" value="{{ $index + 1 }}" {{ $choice->is_correct ? 'checked' : '' }} class="text-indigo-600">
                            <x-text-input class="block w-full" type="text" name="choices[]" value="{{ $choice->choice_text }}" required />
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <x-input-label for="explanation" value="解説" />
                        <textarea id="explanation" name="explanation" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('explanation', $quiz->explanation) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <x-primary-button>更新する</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>