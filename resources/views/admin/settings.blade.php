@section('title', __('Settings'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Settings') }}
        </h2>
    </x-slot>    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Display Errors on Top -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')                  
                    
                    <div>
                        <x-input-label for="terms_condition" :value="__('Terms & Condition')" />
                        <textarea name="settings[terms_condition]" id="terms_condition" rows="10" cols="80" class="w-full">{{ old('settings.terms_condition', $terms_condition) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('settings.terms_condition')" />
                    </div>

                    <div class="mt-5">
                        <x-input-label for="privacy_policy" :value="__('Privacy Policy')" />
                        <textarea name="settings[privacy_policy]" id="privacy_policy" rows="10" cols="80" class="w-full">{{ old('settings.privacy_policy', $privacy_policy) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('settings.privacy_policy')" />
                    </div>

                    <div class="mt-5">
                        <x-input-label for="auction_expire_minutes" :value="__('Auction Expire Minutes')" />
                        <x-text-input id="auction_expire_minutes" name="settings[auction_expire_minutes]" type="text" class="mt-1 block w-full" :value="old('auction_expire_minutes', $auction_expire_minutes)" autocomplete="auction_expire_minutes" />
                        <x-input-error class="mt-2" :messages="$errors->get('settings.auction_expire_minutes')" />
                    </div>

                    <div class="mt-5">
                        <x-input-label for="list_per_page" :value="__('List/Page')" />
                        <x-text-input id="list_per_page" name="settings[list_per_page]" type="text" class="mt-1 block w-full" :value="old('list_per_page', $list_per_page)" autocomplete="list_per_page" />
                        <x-input-error class="mt-2" :messages="$errors->get('settings.list_per_page')" />
                    </div>

                    <div class="flex items-center gap-4 mt-3">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>
                </form>                
            </div>
        </div>
    </div>

    <script>
        const lang = @json(getJSLang('settings'));
        const BASE_API_URL = "{{ url('/api/backend/settings/') }}";
        const autoCloseAddPopup = true;
    </script>
    <script src="https://cdn.tiny.cloud/1/miguh24g9nnuc0d65gtvvur4r7p4lqyx0ut0s9prrzwlstsr/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>            
            tinymce.init({
                selector: 'textarea#terms_condition, textarea#privacy_policy',
                // plugins: ' link image table',
                plugins: 'code',
                menubar: '',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | code',
            });            
    </script>
</x-app-layout>