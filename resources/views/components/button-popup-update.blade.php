<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
