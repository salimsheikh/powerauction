<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-slate-800 dark:border-gray-600">
                <div class="p-6 text-gray-900">
                    <x-add-item-poupup>
                        <x-popup-textbox type="text" name="category_name" label="{{__('Category Name:')}}" class="required" maxlength="50" value="test" />
                        <x-popup-textbox type="text" name="base_price" label="{{__('Base Price:')}}" class="required price_validate" maxlength="10" value="2.5"  />
                        <x-popup-textbox type="text" name="description" label="{{__('Description:')}}" class="required" maxlength="255" value="test" />
                        <x-popup-textbox type="color" name="color_code" label="{{__('Color Code:')}}" value="#000001" class="color_code" />
                    </x-add-item-poupup>

                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                        <div>
                            <button
                                class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                type="button">
                                {{ __('Add New Category') }}
                            </button>

                            <button id="addItemButton"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Add Item
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
                        <div class="table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Base Price</th>
                                        <th>Color Code</th>
                                        <th>Description</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->category_name }}</td>
                                            <td>{{ $category->base_price }}</td>
                                            <td>{{ $category->color_code }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn edit-btn">Edit</a>
                                                <a href="#" class="btn delete-btn">Delete</a>
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




<div class="relative overflow-x-auto shadow-md sm:rounded-lg">


</div>
