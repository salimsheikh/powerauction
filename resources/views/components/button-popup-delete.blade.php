<button {{ $attributes->merge(['type' => 'submit', 'id' => 'btnPopupDelete', 'class' => 'ripple-btn btn_delete px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
