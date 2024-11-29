@section('title', __('Auction'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Auction') }}
        </h2>
    </x-slot>    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900">
                    
                    <div class="auction_header flex justify-between items-center mb-3 dark:text-white text-lg ">
                        <h2>{{ __('messages.auction_league', ['league_name' => $leagueName]) }}</h2>
                        
                            <x-popup-form-input type="select" id="auction_category_id" name="category_id" label="{{ __('Category Name:') }}" />
                        
                    </div>                   

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="table-container" id="tableContainer">
                            <table class="custom-table">
                                <thead id="table-head">
                                    <tr>
                                        <th>{{ __('#ID') }}</th>
                                        <th>{{ __('Category Name') }}</th>
                                        <th>{{ __('Base Price') }}</th>
                                        <th>{{ __('Color Code') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th class="actions">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <tr>
                                        <td colspan="6">
                                            <p class="text-center text-gray-800 dark:text-white">{{__('Please wait! loading table.')}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    <div class="my-4 lg:flex justify-between items-center text-gray-900 dark:text-white">
                        <div id="pagination-info"></div>
                        <ul class="custom-pagination" id="pagination"></ul>                        
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('category'));
        const BASE_API_URL = "{{ url('/api/backend/categories/') }}";
    </script>
</x-app-layout>