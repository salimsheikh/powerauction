<button {{ $attributes->merge(['type' => 'button', 'class' => 'popupCloseModel px-4 py-2 bg-gray-600 text-gray-200 rounded hover:bg-gray-700']) }}>
    {{ $slot }}
</button>
