<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ¿Y Laravel qué es?
            </h2>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('quizzes.generate') }}" method="POST" class="flex items-center gap-2 bg-indigo-50 p-2 rounded-lg border border-indigo-100">
                    @csrf
                    <select name="category" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="Laravel">Laravel</option>
                        <option value="Docker">Docker</option>
                        <option value="Tailwind CSS">Tailwind CSS</option>
                        <option value="JavaScript">JavaScript</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        AIで1問生成
                    </button>
                </form>

                <a href="{{ route('quizzes.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-sm transition">
                    + 手動作成
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($quizzes as $quiz)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded">
                                    {{ $quiz->category }}
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('quizzes.edit', $quiz) }}" class="text-gray-400 hover:text-indigo-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-6 leading-relaxed">
                                {!! nl2br(e($quiz->question)) !!}
                            </h3>

                            <div class="grid grid-cols-1 gap-3 mb-6">
                                @foreach ($quiz->choices->shuffle() as $choice)
                                    <button type="button"
                                            data-quiz-id="{{ $quiz->id }}" 
                                            data-is-correct="{{ $choice->is_correct ? 'true' : 'false' }}"
                                            onclick="checkChoice(this)" 
                                            class="quiz-option-btn w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition duration-200 focus:outline-none">
                                        <span class="inline-block w-6 h-6 mr-2 bg-gray-100 text-center rounded-full text-sm font-bold text-gray-500">
                                            {{ $loop->iteration }}
                                        </span>
                                        {{ $choice->choice_text }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div id="result-{{ $quiz->id }}" class="p-4 rounded hidden text-sm font-bold mb-2"></div>
                            <div id="explanation-{{ $quiz->id }}" class="p-4 bg-blue-50 text-blue-800 rounded hidden text-sm">
                                <p class="font-bold mb-1">【解説】</p>
                                <p>{!! nl2br(e($quiz->explanation)) !!}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500">まだクイズが登録されていません。</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function checkChoice(button) {
            const quizId = button.getAttribute('data-quiz-id');
            const isCorrect = button.getAttribute('data-is-correct') === 'true';
            const resultDiv = document.getElementById(`result-${quizId}`);
            const explanationDiv = document.getElementById(`explanation-${quizId}`);

            const siblingButtons = document.querySelectorAll(`button[data-quiz-id="${quizId}"]`);
            siblingButtons.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });

            if (isCorrect) {
                button.classList.remove('opacity-50');
                button.classList.add('bg-green-100', 'border-green-500', 'ring-2', 'ring-green-500', 'opacity-100');
                resultDiv.textContent = '🎉 正解！ ¡Excelente!';
                resultDiv.className = 'p-4 rounded text-sm font-bold bg-green-100 text-green-800 mb-2';
            } else {
                button.classList.remove('opacity-50');
                button.classList.add('bg-red-100', 'border-red-500', 'ring-2', 'ring-red-500', 'opacity-100');
                resultDiv.textContent = '❌ 残念！';
                resultDiv.className = 'p-4 rounded text-sm font-bold bg-red-100 text-red-800 mb-2';
            }
            resultDiv.classList.remove('hidden');
            explanationDiv.classList.remove('hidden');
        }
    </script>
</x-app-layout>