@props(['id', 'title'])

<div id="{{ $id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">{{ $title }}</h3>
            <button onclick="document.getElementById('{{ $id }}').classList.add('hidden')" 
                    class="text-gray-500 hover:text-gray-700 text-2xl">
                {{-- × --}}
                &times;
            </button>
        </div>
        
        {{ $slot }}
    </div>
</div>