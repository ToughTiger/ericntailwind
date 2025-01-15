<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Eric Solutions - Clinical Trial Expert</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Eric Solutions - Clinical Trial Expert" />
        <link rel="icon"  href="{{ URL::asset('assets/imgs/logos/favicon.ico')}}" />
        <!-- Vendor CSS -->   
        <link rel="stylesheet" href="{{ URL::asset('assets/css/animate.min.css?v=2.0') }}" />
        <link rel="stylesheet" href="{{ URL::asset('assets/css/slick.css?v=2.0') }}" />
        <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css?v=2.0') }}" />
        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ URL::asset('assets/css/tailwind-built.css?v=2.0') }}" />
        <!-- <link rel="stylesheet" href="https://unpkg.com/@themesberg/flowbite@1.2.0/dist/flowbite.min.css" /> -->
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head> 
    <body class="bg-white text-body font-body">
    <div class="main">
            @include('layouts.navigation')          

            <!-- Page Content -->
          
                {{ $slot }}
        
        </div>
   
        <section class="py-20 bg-top bg-no-repeat" style="background-image: url('assets/imgs/elements/blob.svg')">
                <div class="container">
                    <div class="relative py-20 px-4 lg:p-20">
                        <div class="max-w-lg mx-auto text-center">
                            <h2 class="mb-4 text-3xl lg:text-4xl font-bold font-heading wow animate__animated animate__fadeInUp">
                                <span>Subscribe now to</span>
                                <span class="text-blue-500">Our Newsletter</span>
                                <span>and get the Coupon code.</span>
                            </h2>
                            <p class="mb-8 text-blueGray-400 wow animate__animated animate__fadeInUp" data-wow-delay=".3s">All your information is completely confidential</p>
                            <div class="p-4 bg-white rounded-lg flex flex-wrap max-w-md mx-auto wow animate__animated animate__fadeInUp" data-wow-delay=".5s">
                                <div class="flex w-full md:w-2/3 px-3 mb-3 md:mb-0 md:mr-6 bg-blueGray-100 rounded">
                                    <svg class="h-6 w-6 my-auto text-blueGray-500" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    <input class="w-full pl-3 py-4 text-xs text-blueGray-400 font-semibold leading-none bg-blueGray-100 outline-none" type="text" placeholder="Type your e-mail" />
                                </div>
                                <button class="w-full md:w-auto py-4 px-8 text-xs text-white font-semibold leading-none bg-blue-500 hover:bg-blue-700 rounded" type="submit">Sign Up</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-20">
                <div class="container wow animate__animated animate__fadeIn" data-wow-delay=".3s">
                    <div class="flex flex-wrap mb-12 lg:mb-20 -mx-3 text-center lg:text-left">
                        <div class="w-full lg:w-1/5 px-3 mb-6 lg:mb-0">
                            <a class="inline-block mx-auto lg:mx-0 text-3xl font-semibold leading-none" href="index.html">
                                <img class="h-10" src="assets/imgs/logos/monst-logo.svg" alt="" />
                            </a>
                        </div>
                        <div class="w-full lg:w-2/5 px-3 mb-8 lg:mb-0">
                            <p class="max-w-md mx-auto lg:max-w-full lg:mx-0 lg:pr-32 lg:text-lg text-blueGray-400 leading-relaxed">Helping you <strong>maximize</strong> operations management with digitization</p>
                        </div>
                        <div class="w-full lg:w-1/5 px-3 mb-8 lg:mb-0">
                            <p class="mb-2 lg:mb-4 lg:text-lg font-bold font-heading text-blueGray-800">Office</p>
                            <p class="lg:text-lg text-blueGray-400">359 Hidden Valley Road, NY</p>
                        </div>
                        <div class="w-full lg:w-1/5 px-3">
                            <p class="mb-2 lg:mb-4 lg:text-lg font-bold font-heading text-blueGray-800">Contacts</p>
                            <p class="lg:text-lg text-blueGray-400">(+01) 234 568</p>
                            <p class="lg:text-lg text-blueGray-400">contact@monst.com</p>
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row items-center lg:justify-between">
                        <p class="text-sm text-blueGray-400">&copy; 2021. All rights reserved. Designed by <a class="text-blue-400" href="https://alithemes.com" target="_blank">Alithemes.com</a></p>
                        <div class="order-first lg:order-last -mx-2 mb-4 lg:mb-0">
                            <a class="inline-block px-2" href="#">
                                <img src="assets/imgs/icons/facebook-blue.svg" alt="" />
                            </a>
                            <a class="inline-block px-2" href="#">
                                <img src="assets/imgs/icons/twitter-blue.svg" alt="" />
                            </a>
                            <a class="inline-block px-2" href="#">
                                <img src="assets/imgs/icons/instagram-blue.svg" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- End .main -->

        <!-- Preloader Start -->
        <!-- <div id="preloader-active">
            <div class="preloader flex-1 content-center">
                <div class="logo absolute inset-y-2/4 jump">
                    <img src="assets/imgs/logos/favicon.svg" alt="" />
                    <h1 class="text-lg font-semibold">Monst</h1>
                </div>
            </div>
        </div> -->
        <!--Import Vendor Js-->
        <script src="{{URL::asset('assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/waypoints.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/counterup.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/slick.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/wow.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/scrollup.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/smooth.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/textType.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/mobile-menu.js') }}"></script>
     
        <script src="{{ URL::asset('assets/js/main.js?v=2.0') }}"></script>
    </body>
</html>

