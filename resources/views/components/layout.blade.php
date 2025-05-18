<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WN9XWGKG');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DG3HYM0N5T"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-DG3HYM0N5T');
    </script>
    <title>Eric Solutions - Clinical Trial Expert</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Eric Solutions - Clinical Trial Expert" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/imgs/logos/favicon.ico') }}" />
    <!-- Vendor CSS -->

    <link rel="stylesheet" href="{{ URL::asset('assets/css/animate.min.css?v=2.0') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/slick.css?v=2.0') }}" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/tailwind-built.css?v=2.0') }}" />

    <link rel="stylesheet" href="{{ URL::asset('assets/css/normalize.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css?v=2.0') }}" />
</head>

<body class="bg-white text-body font-body">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WN9XWGKG"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="main">
        <x-navigation />
        {{ $slot }}
    </div>

    <section class="py-20 bg-top bg-no-repeat bg-footer">
        <div class="container">
            <div class="relative py-20 px-4 lg:p-20">
                <div class="max-w-lg mx-auto text-center">
                    <h2
                        class="mb-4 text-3xl lg:text-4xl font-bold font-heading wow animate__animated animate__fadeInUp">
                        <span>Register for</span>
                        <span class="text-white">Free Demo</span>
                        <!-- <span>and get a discount Coupon.</span> -->
                    </h2>
                    <!-- <p class="mb-8 text-blueGray-400 wow animate__animated animate__fadeInUp" data-wow-delay=".3s">All your information is completely confidential</p> -->
                    <div class="p-8 bg-transparent rounded-lg flex justify-center items-center flex-wrap max-w-md mx-auto wow animate__animated animate__fadeInUp"
                        data-wow-delay=".5s">


                        <a href="/contact"
                            class="flex items-center justify-center w-full md:w-auto py-4 px-8 text-white font-bold leading-none bg-blue-800 hover:bg-blue-700 rounded"
                            type="submit">
                            <svg class="h-6 w-6 inline mr-3" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
                            </svg>
                            Register Now</a>

                    </div>
                </div>
            </div>
    </section>
    <section class="py-20">
        <div class="container bg-right-bottom bg-no-repeat bg-center file" style="background-image:url('assets/imgs/elements/blob.svg')">
            <div class="flex flex-wrap mb-12 lg:mb-20 -mx-3 text-center lg:text-left">
                <div class="w-full lg:w-1/3 px-3 mb-8 lg:mb-0">
                    <img class="h-32 w-32" src="{{URL::asset('assets/imgs/logos/final_black.gif')}}"
                        alt="eric solutions logo" />

                    <p class="lg:text-sm text-blueGray-400"><svg fill="#020b31" version="1.1" id="Capa_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="h-4 w-4 mr-3 inline" viewBox="0 0 395.71 395.71" xml:space="preserve">
                            <g>
                                <path d="M197.849,0C122.131,0,60.531,61.609,60.531,137.329c0,72.887,124.591,243.177,129.896,250.388l4.951,6.738
		c0.579,0.792,1.501,1.255,2.471,1.255c0.985,0,1.901-0.463,2.486-1.255l4.948-6.738c5.308-7.211,129.896-177.501,129.896-250.388
		C335.179,61.609,273.569,0,197.849,0z M197.849,88.138c27.13,0,49.191,22.062,49.191,49.191c0,27.115-22.062,49.191-49.191,49.191
		c-27.114,0-49.191-22.076-49.191-49.191C148.658,110.2,170.734,88.138,197.849,88.138z" />
                            </g>
                        </svg>621 E Tropical Way Plantation, FLorida 33317</p>

                    <p class="lg:text-sm text-blueGray-400 mt-3">
                        <svg class="h-4 w-4 inline mr-3" viewBox="0 0 24 24" fill="#020b31"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.1007 13.359L15.5719 12.8272H15.5719L16.1007 13.359ZM16.5562 12.9062L17.085 13.438H17.085L16.5562 12.9062ZM18.9728 12.5894L18.6146 13.2483L18.9728 12.5894ZM20.8833 13.628L20.5251 14.2869L20.8833 13.628ZM21.4217 16.883L21.9505 17.4148L21.4217 16.883ZM20.0011 18.2954L19.4723 17.7636L20.0011 18.2954ZM18.6763 18.9651L18.7459 19.7119H18.7459L18.6763 18.9651ZM8.81536 14.7266L9.34418 14.1947L8.81536 14.7266ZM4.00289 5.74561L3.2541 5.78816L3.2541 5.78816L4.00289 5.74561ZM10.4775 7.19738L11.0063 7.72922H11.0063L10.4775 7.19738ZM10.6342 4.54348L11.2346 4.09401L10.6342 4.54348ZM9.37326 2.85908L8.77286 3.30855V3.30855L9.37326 2.85908ZM6.26145 2.57483L6.79027 3.10667H6.79027L6.26145 2.57483ZM4.69185 4.13552L4.16303 3.60368H4.16303L4.69185 4.13552ZM12.0631 11.4972L12.5919 10.9654L12.0631 11.4972ZM16.6295 13.8909L17.085 13.438L16.0273 12.3743L15.5719 12.8272L16.6295 13.8909ZM18.6146 13.2483L20.5251 14.2869L21.2415 12.9691L19.331 11.9305L18.6146 13.2483ZM20.8929 16.3511L19.4723 17.7636L20.5299 18.8273L21.9505 17.4148L20.8929 16.3511ZM18.6067 18.2184C17.1568 18.3535 13.4056 18.2331 9.34418 14.1947L8.28654 15.2584C12.7186 19.6653 16.9369 19.8805 18.7459 19.7119L18.6067 18.2184ZM9.34418 14.1947C5.4728 10.3453 4.83151 7.10765 4.75168 5.70305L3.2541 5.78816C3.35456 7.55599 4.14863 11.144 8.28654 15.2584L9.34418 14.1947ZM10.7195 8.01441L11.0063 7.72922L9.9487 6.66555L9.66189 6.95073L10.7195 8.01441ZM11.2346 4.09401L9.97365 2.40961L8.77286 3.30855L10.0338 4.99296L11.2346 4.09401ZM5.73263 2.04299L4.16303 3.60368L5.22067 4.66736L6.79027 3.10667L5.73263 2.04299ZM10.1907 7.48257C9.66189 6.95073 9.66117 6.95144 9.66045 6.95216C9.66021 6.9524 9.65949 6.95313 9.659 6.95362C9.65802 6.95461 9.65702 6.95561 9.65601 6.95664C9.65398 6.95871 9.65188 6.96086 9.64972 6.9631C9.64539 6.96759 9.64081 6.97245 9.63599 6.97769C9.62634 6.98816 9.61575 7.00014 9.60441 7.01367C9.58174 7.04072 9.55605 7.07403 9.52905 7.11388C9.47492 7.19377 9.41594 7.2994 9.36589 7.43224C9.26376 7.70329 9.20901 8.0606 9.27765 8.50305C9.41189 9.36833 10.0078 10.5113 11.5343 12.0291L12.5919 10.9654C11.1634 9.54499 10.8231 8.68059 10.7599 8.27309C10.7298 8.07916 10.761 7.98371 10.7696 7.96111C10.7748 7.94713 10.7773 7.9457 10.7709 7.95525C10.7677 7.95992 10.7624 7.96723 10.7541 7.97708C10.75 7.98201 10.7451 7.98759 10.7394 7.99381C10.7365 7.99692 10.7335 8.00019 10.7301 8.00362C10.7285 8.00534 10.7268 8.00709 10.725 8.00889C10.7241 8.00979 10.7232 8.0107 10.7223 8.01162C10.7219 8.01208 10.7212 8.01278 10.7209 8.01301C10.7202 8.01371 10.7195 8.01441 10.1907 7.48257ZM11.5343 12.0291C13.0613 13.5474 14.2096 14.1383 15.0763 14.2713C15.5192 14.3392 15.8763 14.285 16.1472 14.1841C16.28 14.1346 16.3858 14.0763 16.4658 14.0227C16.5058 13.9959 16.5392 13.9704 16.5663 13.9479C16.5799 13.9367 16.5919 13.9262 16.6024 13.9166C16.6077 13.9118 16.6126 13.9073 16.6171 13.903C16.6194 13.9008 16.6215 13.8987 16.6236 13.8967C16.6246 13.8957 16.6256 13.8947 16.6266 13.8937C16.6271 13.8932 16.6279 13.8925 16.6281 13.8923C16.6288 13.8916 16.6295 13.8909 16.1007 13.359C15.5719 12.8272 15.5726 12.8265 15.5733 12.8258C15.5735 12.8256 15.5742 12.8249 15.5747 12.8244C15.5756 12.8235 15.5765 12.8226 15.5774 12.8217C15.5793 12.82 15.581 12.8183 15.5827 12.8166C15.5862 12.8133 15.5895 12.8103 15.5926 12.8074C15.5988 12.8018 15.6044 12.7969 15.6094 12.7929C15.6192 12.7847 15.6265 12.7795 15.631 12.7764C15.6403 12.7702 15.6384 12.773 15.6236 12.7785C15.5991 12.7876 15.501 12.8189 15.3038 12.7886C14.8905 12.7253 14.02 12.3853 12.5919 10.9654L11.5343 12.0291ZM9.97365 2.40961C8.95434 1.04802 6.94996 0.83257 5.73263 2.04299L6.79027 3.10667C7.32195 2.578 8.26623 2.63181 8.77286 3.30855L9.97365 2.40961ZM4.75168 5.70305C4.73201 5.35694 4.89075 4.9954 5.22067 4.66736L4.16303 3.60368C3.62571 4.13795 3.20329 4.89425 3.2541 5.78816L4.75168 5.70305ZM19.4723 17.7636C19.1975 18.0369 18.9029 18.1908 18.6067 18.2184L18.7459 19.7119C19.4805 19.6434 20.0824 19.2723 20.5299 18.8273L19.4723 17.7636ZM11.0063 7.72922C11.9908 6.7503 12.064 5.2019 11.2346 4.09401L10.0338 4.99295C10.4373 5.53193 10.3773 6.23938 9.9487 6.66555L11.0063 7.72922ZM20.5251 14.2869C21.3429 14.7315 21.4703 15.7769 20.8929 16.3511L21.9505 17.4148C23.2908 16.0821 22.8775 13.8584 21.2415 12.9691L20.5251 14.2869ZM17.085 13.438C17.469 13.0562 18.0871 12.9616 18.6146 13.2483L19.331 11.9305C18.2474 11.3414 16.9026 11.5041 16.0273 12.3743L17.085 13.438Z"
                                fill="#1C274C" />
                        </svg>(+1) 786-636-5556
                    </p>
                    <p class="lg:text-sm text-blueGray-400 mt-3">
                        <svg fill="#020b31" class="h-4 w-4 inline mr-3 " viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22,5V9L12,13,2,9V5A1,1,0,0,1,3,4H21A1,1,0,0,1,22,5ZM2,11.154V19a1,1,0,0,0,1,1H21a1,1,0,0,0,1-1V11.154l-10,4Z" />
                        </svg>
                        Info@ericsolutions.com
                    </p>
                </div>
                <div class="w-full lg:w-1/5 px-3 mb-8 lg:mb-0">
                    <p class="mb-2 lg:mb-4 lg:text-lg font-bold font-heading text-blueGray-800">Company</p>
                    <ul>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">About Us</a></li>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">Services</a></li>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">Blogs</a></li>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">Case Studies</a></li>
                    </ul>


                </div>
                <div class="w-full lg:w-1/5 px-3">
                    <p class="mb-2 lg:mb-4 lg:text-lg font-bold font-heading text-blueGray-800">Legal</p>
                    <ul>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">Privacy Policy</a>
                        </li>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">Terms &
                                Conditions</a></li>
                        <li class="mb-2"><a class="text-blueGray-400 hover:text-blue-500" href="#">Cookie Policy</a>
                        </li>

                </div>
                <div class="w-full lg:w-1/5 px-3">
                    <p class="mb-2 lg:mb-4 lg:text-lg font-bold font-heading text-blueGray-800">Subscribe News Letter
                    </p>
                    <div class="mt-12 mb-16">
                        <!-- <p class="mb-4 text-xs text-blueGray-400 text-center lg:text-left wow animate__animated animate__fadeInUp animated" data-wow-delay=".1s">Subscribe and stay fully connected with product.</p> -->
                        <div class="flex flex-col lg:flex-nowrap items-center wow animate__animated animate__fadeInUp animated"
                            data-wow-delay=".3s">
                            <div class="flex w-full  mb-3 lg:mb-0 px-4 bg-blueGray-200 rounded">
                                <svg class="h-6 w-6 my-auto" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                    </path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <input class="w-full py-4 pl-3 text-xs text-blueGray-400 bg-blueGray-200 outline-none"
                                    type="text" placeholder="Type your e-mail" />
                            </div>
                            <button
                                class="hover-up-2 w-full lg:w-auto py-3 px-8 lg:ml-auto text-xs mt-3 text-white font-semibold leading-none bg-blue-800 hover:bg-blue-500 rounded"
                                type="submit">Sign Up
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex flex-col lg:flex-row items-center lg:justify-between">
                <p class="text-sm text-blueGray-400">&copy; 2025. All rights reserved</p>
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
    @include('cookie-consent::dialogContents')
    </div>
    <!-- End .main -->

    <!-- Preloader Start -->
    <!-- <div id="preloader-active">
        <div class="preloader flex-1 content-center">
            <div class="logo absolute inset-y-2/4 jump">
                <img src="assets/imgs/logos/favicon.ico" alt="eric solutions" />
                <h1 class="text-lg font-semibold">Eric Solutions</h1>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"
        integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"
        integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"
        integrity="sha512-onMTRKJBKz8M1TnqqDuGBlowlH0ohFzMXYRNebz+yOcc5TQr/zAKsthzhuv0hiyUKEiQEQXEynnXCvNTOk50dg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ URL::asset('assets/js/main.js?v=2.0') }}"></script>

    <script src="{{ asset('vendor/filament-ai-tools/ai-tools.js') }}"></script>

    <script>
        document.addEventListener("livewire:init", () => {
            Livewire.on("use-content", ({
                field,
                content
            }) => {
                const modelName = `data.${field}`;

                // Find the input field (this works even if it's hidden by Filament)
                const el = document.querySelector(`[wire\\:model="${modelName}"]`);
                if (!el) {
                    console.warn(`No field found for ${modelName}`);
                    return;
                }

                // Try to set the Alpine.js bound state (for MarkdownEditor)
                const alpineComponent = el.closest('[x-data]');
                if (alpineComponent && alpineComponent.__x) {
                    try {
                        // For MarkdownEditor: set Alpine state
                        alpineComponent.__x.set('state', content);
                    } catch (e) {
                        console.warn('Could not set Alpine state:', e);
                    }
                }

                // Update input value directly (Livewire will see this)
                el.value = content;
                el.dispatchEvent(new Event('input', {
                    bubbles: true
                }));

                // If needed: use Livewire's set manually
                const livewireId = el.closest("[wire\\:id]")?.getAttribute("wire:id");
                const livewireInstance = Livewire.find(livewireId);
                if (livewireInstance) {
                    livewireInstance.set(modelName, content);
                }

                // Optional: visual feedback
                el.classList.add("ring", "ring-green-400", "transition");
                setTimeout(() => el.classList.remove("ring", "ring-green-400"), 1000);
            });
        });
    </script>



</body>

</html>