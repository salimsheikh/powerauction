<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700']) }}>
    {{ $slot }}
</button>
