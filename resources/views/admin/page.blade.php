@section('title', $title)
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{  $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900 dark:text-white">                                  
                    {!! $pageData !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('page'));
    </script>
</x-app-layout>