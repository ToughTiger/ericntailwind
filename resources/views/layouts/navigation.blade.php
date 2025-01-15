<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
   <!--Mobile menu-->
            <div class="hidden navbar-menu relative z-50 transition duration-300">
                <div class="navbar-backdrop fixed inset-0 bg-blueGray-800 opacity-25"></div>
                <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm py-6 px-6 bg-white border-r overflow-y-auto transition duration-300">
                    <div class="flex items-center mb-8">
                        <a class="mr-auto text-3xl font-semibold leading-none" href="#">
                            <img class="h-10" src="assets/imgs/logos/monst-logo.svg" alt="" />
                        </a>
                        <button class="navbar-close">
                            <svg class="h-6 w-6 text-blueGray-400 cursor-pointer hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div>
                        <ul class="mobile-menu">
                            <li class="mb-1 menu-item-has-children rounded-xl">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500 rounded-xl" href="#">Home</a>
                                <ul class="dropdown pl-5">
                                    <li>
                                        <a class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="index.html">Home 1</a>
                                    </li>
                                    <li>
                                        <a class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="index-2.html">Home 2</a>
                                    </li>
                                    <li>
                                        <a class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="index-3.html">Home 3</a>
                                    </li>
                                    <li>
                                        <a class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="index-4.html">Home 4</a>
                                    </li>
                                    <li>
                                        <a class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="index-5.html">Home 5</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="mb-1 rounded-xl">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500 rounded-xl" href="about.html">About Us</a>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="services.html">Services</a>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="portfolio.html">Portfolio</a>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="pricing.html">Pricing</a>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="team.html">Team</a>
                            </li>
                            <li class="mb-1 menu-item-has-children rounded-xl">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="team.html">Blog</a>
                                <ul class="dropdown pl-5">
                                    <li>
                                        <a href="blog.html" class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500">Category 1</a>
                                    </li>
                                    <li>
                                        <a href="blog-2.html" class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500">Category 2</a>
                                    </li>
                                    <li>
                                        <a href="blog-single.html" class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500">Single 1</a>
                                    </li>
                                    <li>
                                        <a href="blog-single-2.html" class="block p-3 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500">Single 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="faqs.html">Faqs</a>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="testimonials.html">Testimonial</a>
                            </li>
                            <li class="mb-1">
                                <a class="block p-4 text-sm text-blueGray-500 hover:bg-blue-50 hover:text-blue-500" href="contact.html">Contact Us</a>
                            </li>
                        </ul>
                        <div class="mt-4 pt-6 border-t border-blueGray-100">
                            <a class="block px-4 py-3 mb-3 text-xs text-center font-semibold leading-none bg-blue-400 hover:bg-blue-500 text-white rounded" href="#">Sign Up</a>
                            <a class="block px-4 py-3 mb-2 text-xs text-center text-blue-500 hover:text-blue-700 font-semibold leading-none border border-blue-200 hover:border-blue-300 rounded" href="#">Log In</a>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <p class="my-4 text-xs text-blueGray-400">
                            <span>Get in Touch</span>
                            <a class="text-blue-500 hover:text-blue-500 underline" href="#">contact@monst.com</a>
                        </p>
                        <a class="inline-block px-1" href="#">
                            <img src="assets/imgs/icons/facebook-blue.svg" alt="" />
                        </a>
                        <a class="inline-block px-1" href="#">
                            <img src="assets/imgs/icons/twitter-blue.svg" alt="" />
                        </a>
                        <a class="inline-block px-1" href="#">
                            <img src="assets/imgs/icons/instagram-blue.svg" alt="" />
                        </a>
                    </div>
                </nav>
            </div>