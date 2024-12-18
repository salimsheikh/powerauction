@section('title', 'Team Details')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Team Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-page">
                <div class="p-6 text-gray-900 dark:text-white">


                    <!-- Slider Container -->
                    <div id="slider" class="relative w-screen h-screen overflow-hidden group">

                        <!-- Slides -->
                        <div class="absolute inset-0 flex transition-transform duration-700" id="slides">

                            <!-- Slide 1 -->
                            <div class="w-screen h-screen flex-shrink-0 bg-gray-100 dark:bg-gray-800 p-8">
                                <!-- First Row -->
                                <div class="container mx-auto p-4 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <!-- Left Column (Image) -->
                                        <div class="flex items-center justify-center">
                                            <img src="https://via.placeholder.com/300" alt="Placeholder Image"
                                                class="rounded-lg shadow-lg">
                                        </div>

                                        <!-- Right Column (Four Gradient Boxes) -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div
                                                class="p-4 bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 text-white rounded-lg shadow-md">
                                                <label class="text-sm font-bold block">Label 1</label>
                                                <p class="text-lg font-semibold mt-1">Value 1</p>
                                            </div>
                                            <div
                                                class="p-4 bg-gradient-to-br from-blue-500 via-green-500 to-teal-500 text-white rounded-lg shadow-md">
                                                <label class="text-sm font-bold block">Label 2</label>
                                                <p class="text-lg font-semibold mt-1">Value 2</p>
                                            </div>
                                            <div
                                                class="p-4 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 text-white rounded-lg shadow-md">
                                                <label class="text-sm font-bold block">Label 3</label>
                                                <p class="text-lg font-semibold mt-1">Value 3</p>
                                            </div>
                                            <div
                                                class="p-4 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 text-white rounded-lg shadow-md">
                                                <label class="text-sm font-bold block">Label 4</label>
                                                <p class="text-lg font-semibold mt-1">Value 4</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Row (Table) -->
                                    <div class="overflow-x-auto">
                                        <table
                                            class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-600">
                                            <thead>
                                                <tr class="bg-gray-200 dark:bg-gray-700">
                                                    <th
                                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                                        Header 1</th>
                                                    <th
                                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                                        Header 2</th>
                                                    <th
                                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                                        Header 3</th>
                                                    <th
                                                        class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">
                                                        Header 4</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 1</td>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 2</td>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 3</td>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 4</td>
                                                </tr>
                                                <tr>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 5</td>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 6</td>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 7</td>
                                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                                        Data 8</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Add more slides as needed -->
                            <div
                                class="w-screen h-screen flex-shrink-0 bg-blue-200 dark:bg-blue-800 p-8 flex items-center justify-center">
                                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">Slide 2 Content Here
                                </h1>
                            </div>

                        </div>

                        <!-- Hover Button -->
                        <button id="slider-toggle"
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black/50 text-white px-6 py-2 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Pause
                        </button>

                        <!-- Navigation Dots -->
                        <div class="absolute bottom-4 w-full flex justify-center space-x-2">
                            <div class="dot w-3 h-3 bg-gray-500 rounded-full cursor-pointer" data-index="0"></div>
                            <div class="dot w-3 h-3 bg-gray-500 rounded-full cursor-pointer" data-index="1"></div>
                            <!-- Add more dots as needed for additional slides -->
                        </div>
                    </div>

                    <script>
                        let currentSlide = 0;
                        const slides = document.getElementById('slides');
                        const totalSlides = slides.children.length;
                        const dots = document.querySelectorAll('.dot');
                        let isPlaying = true;

                        // Update active dot
                        const updateDots = () => {
                            dots.forEach((dot, index) => {
                                dot.classList.toggle('bg-gray-900', index === currentSlide);
                                dot.classList.toggle('bg-gray-500', index !== currentSlide);
                            });
                        };

                        // Function to show the next slide
                        const showNextSlide = () => {
                            currentSlide = (currentSlide + 1) % totalSlides;
                            slides.style.transform = `translateX(-${currentSlide * 100}%)`;
                            updateDots();
                        };

                        // Auto-play interval
                        let slideInterval = setInterval(showNextSlide, 3000);

                        // Pause on hover if playing
                        const slider = document.getElementById('slider');
                        slider.addEventListener('mouseenter', () => {
                            if (isPlaying) {
                                clearInterval(slideInterval);
                            }
                        });
                        slider.addEventListener('mouseleave', () => {
                            if (isPlaying) {
                                slideInterval = setInterval(showNextSlide, 3000);
                            }
                        });

                        // Toggle play/pause functionality
                        document.getElementById('slider-toggle').addEventListener('click', () => {
                            if (isPlaying) {
                                clearInterval(slideInterval);
                                document.getElementById('slider-toggle').innerText = 'Play';
                            } else {
                                slideInterval = setInterval(showNextSlide, 3000);
                                document.getElementById('slider-toggle').innerText = 'Pause';
                            }
                            isPlaying = !isPlaying;
                        });

                        // Dot navigation
                        dots.forEach(dot => {
                            dot.addEventListener('click', () => {
                                currentSlide = parseInt(dot.dataset.index);
                                slides.style.transform = `translateX(-${currentSlide * 100}%)`;
                                updateDots();
                            });
                        });

                        // Initialize dots
                        updateDots();
                    </script>


                </div>
            </div>
        </div>
    </div>
    <script>
        const lang = @json(getJSLang('page'));
    </script>
</x-app-layout>
