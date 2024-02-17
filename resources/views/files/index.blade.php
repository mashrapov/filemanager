<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Управление файлами
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 bg-white border-b border-gray-200">
                <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                        <input type="file" name="file" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <x-button>
                        {{ __('Загрузить файл') }}
                    </x-button>

                </form>

                <div class="mt-6">
                    <h3 class="mb-4 text-lg font-semibold">Список файлов</h3>
                    @foreach ($files as $file)
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="text-gray-600">{{ $file->name }}</div>
                            <div class="flex items-center">
                                <button onclick="copyToClipboard('{{ asset('storage/' . str_replace('public/', '', $file->path)) }}')" class="px-2 py-1 text-black bg-blue-600 rounded hover:bg-blue-800">
                                    Копировать URL
                                </button>
                                <a href="{{ route('files.delete', $file->id) }}" class="text-red-600 hover:text-red-900">Удалить</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyToClipboardFallback(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'успешно' : 'неудачно';
                console.log('Fallback: Текст скопирован ' + msg);
            } catch (err) {
                console.error('Fallback: Ошибка при копировании', err);
            }

            document.body.removeChild(textArea);
        }

        function copyToClipboard(text) {
            if (!navigator.clipboard) {
                copyToClipboardFallback(text);
                return;
            }
            navigator.clipboard.writeText(text).then(function() {
                alert('URL скопирован в буфер обмена');
            }, function(err) {
                console.error('Не удалось скопировать URL: ', err);
            });
        }

    </script>
</x-app-layout>
