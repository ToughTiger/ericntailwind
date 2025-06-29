<x-layout>
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-down { animation: fadeInDown 1s ease-out; }
        .animate-fade-up { animation: fadeInUp 1s ease-out; }
        .animate-slide-left { animation: slideInLeft 0.8s ease-out; }
        .animate-slide-right { animation: slideInRight 0.8s ease-out; }
        .animation-delay-300 { animation-delay: 0.3s; animation-fill-mode: both; }
        .animation-delay-600 { animation-delay: 0.6s; animation-fill-mode: both; }
        .animation-delay-900 { animation-delay: 0.9s; animation-fill-mode: both; }
        .animation-delay-1200 { animation-delay: 1.2s; animation-fill-mode: both; }
        .animation-delay-1500 { animation-delay: 1.5s; animation-fill-mode: both; }
        .animation-delay-1800 { animation-delay: 1.8s; animation-fill-mode: both; }
    </style>
    <section class="hero-1">
        <div class="container bg-right-bottom bg-cover file" style="background-image:url('assets/imgs/elements/blob.svg')">
            <div class="lg:flex items-center justify-center  mb-12 lg:mb-20 mx-3 sm:flex-col-reverse text-center lg:text-left">
                <div class="flex max-h-max w-full lg:w-1/2  px-3 mb-8 lg:mb-0 mx-auto">
                    <div class="image object-center text-center">
                        <img src="https://i.imgur.com/WbQnbas.png">
                    </div>
                </div>
                <div class="sm:w-1/2 p-5 lg:w-1/2 px-3">
                    <div class="text">
                       
                         <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-down">
                            About <span class="text-yellow-300">Eric Solutions</span>
                        </h1>
                        <p class="text-xl md:text-2xl font-light opacity-90 animate-fade-up animation-delay-300">
                            Custom Solutions for Thought Leadership and Research Development
                        </p>
                        <div class="text-sm mt-4 opacity-75 animate-fade-up animation-delay-600">
                            www.ericsolutions.com
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container py-10 bg-white">   <!-- Mission Statement -->
        <div class="container bg-white/95 backdrop-blur-sm rounded-2xl p-8 md:p-12 mb-16 shadow-lg animate-fade-up animation-delay-600">
            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Our Mission</h3>
            <blockquote class="text-left text-lg md:text-xl text-gray-700 italic leading-relaxed ">
                We are dedicated to delivering comprehensive, end-to-end services and solutions to support our 
                clients' <span class="text-blue-600 font-semibold">clinical development programs</span>, and ensuring the highest quality and efficiency throughout the process.
            </blockquote>
        </div>

        <!-- Timeline Section -->
        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-yellow-400 via-orange-400 to-red-400 rounded-full"></div>
            
            <!-- Timeline Items -->
            <div class="space-y-16">
                <!-- 2016 -->
                <div class="flex flex-col md:flex-row items-center animate-slide-left animation-delay-900">
                    <div class="md:w-1/2 md:pr-8 mb-4 md:mb-0">
                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="bg-gray-800 text-white px-4 py-2 rounded-lg font-bold text-xl">2016</div>
                                <div class="ml-4">
                                   <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">ERIC Started</h3>
                            <p class="text-gray-600">
                                Technology Company with flagship product of Electronic Remote Informed Consent (ERIC)
                            </p>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="w-6 h-6 bg-yellow-400 rounded-full border-4 border-white shadow-lg z-10"></div>
                    </div>
                    <div class="md:w-1/2 md:pl-8"></div>
                </div>

                <!-- 2021 -->
                <div class="flex flex-col md:flex-row items-center animate-slide-right animation-delay-1200">
                    
                    <div class="md:w-1/2 md:pl-8 mb-4 md:mb-0">
                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-xl">2021</div>
                                <div class="ml-4">
                                    <svg class="w-12 h-12 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">End-End Services</h3>
                            <p class="text-gray-600">
                                Build up the capacity to undertake full scope clinical trials including clinical safety.
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="w-6 h-6 bg-orange-400 rounded-full border-4 border-white shadow-lg z-10"></div>
                    </div>
                    <div class="md:w-1/2 md:pr-8"></div>
                </div>

                <!-- 2022 -->
                <div class="flex flex-col md:flex-row items-center animate-slide-left animation-delay-1500">
                    <div class="md:w-1/2 md:pr-8 mb-4 md:mb-0">
                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold text-xl">2022</div>
                                <div class="ml-4">
                                   <svg class="w-12 h-12 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM11 19.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Global Expansion</h3>
                            <p class="text-gray-600">
                                Expanded presence in Australia through Partner to execute Phase I clinical trials.
                            </p>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="w-6 h-6 bg-red-400 rounded-full border-4 border-white shadow-lg z-10"></div>
                    </div>
                    <div class="md:w-1/2 md:pl-8"></div>
                </div>

                <!-- 2023 -->
                <div class="flex flex-col md:flex-row items-center animate-slide-right animation-delay-1800">
                    
                    <div class="md:w-1/2 md:pl-8 mb-4 md:mb-0">
                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold text-xl">2023</div>
                                <div class="ml-4">
                                    <svg class="w-12 h-12 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Team Expansion</h3>
                            <p class="text-gray-600">
                                Expanded Operations, Clinical Data Management team in USA, India
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
</x-layout>