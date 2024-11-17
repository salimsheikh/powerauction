<button {{ $attributes->merge(['type' => 'submit', 'id' => 'btnPopupDelete', 'class' => 'btn_delete px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700']) }}>
    {{ $slot }}
</button>
