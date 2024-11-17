<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <!-- Add Item Popup -->
    <x-popup-add-item-model>
        <x-popup-form-input type="text" name="category_name" label="{{ __('Category Name:') }}" class="required"
            maxlength="50" value="test" />
        <x-popup-form-input type="text" name="base_price" label="{{ __('Base Price:') }}"
            class="required price_validate" maxlength="10" value="2.5" />
        <x-popup-form-input type="text" name="description" label="{{ __('Description:') }}" class="required"
            maxlength="255" value="test" />
        <x-popup-form-input type="color" name="color_code" label="{{ __('Color Code:') }}" value="#000001"
            class="color_code" />
    </x-popup-add-item-model>

    <!-- Add Item Popup -->
    <x-popup-delete-item-model>
        {{ __('Do you want to delete category?') }}
    </x-popup-delete-item-model>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-slate-800 dark:border-gray-600">
                <div class="p-6 text-gray-900">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                        <div>
                            <button id="buttonPopupShowAddItemModel"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Add Category') }}
                            </button>
                        </div>
                        <div class="pb-4 search-input">
                            <label for="table-search" class="sr-only">{{ __('Search') }}</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="table-search"
                                    class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="{{ __('Search for items') }}">
                            </div>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="table-container" id="tableContainer">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('#ID') }}</th>
                                        <th>{{ __('Category Name') }}</th>
                                        <th>{{ __('Base Price') }}</th>
                                        <th>{{ __('Color Code') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->category_name }}</td>
                                            <td>{{ $category->base_price }}</td>
                                            <td>{{ $category->color_code }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn edit-btn">{{__('Edit')}}</a>
                                                <a href="#" class="btn delete-btn delete-button"
                                                    data-id={{ $category->id }}>{{__('Delete')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Pagination Links -->
                    <div class="my-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const BASE_API_URL = "{{ url('/api/backend/categories/') }}";
</script>
