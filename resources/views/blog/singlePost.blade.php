<x-layout>
    <section class="pb-20">
        <div class="pt-20 pb-8 mb-12 bg-cover bg-no-repeat">
            <div class="container">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center mb-6">
                        <span class="text-base md:text-lg">
                            @foreach ( $post->categories as $category )
                            <a class="text-blueGray-400 hover:underline" href="#"><span class="inline-block py-1 px-3 text-xs font-semibold bg-blue-100 text-blue-600 rounded-xl mr-3">{{ $category->name }}</span></a>
                            @endforeach

                            <span class="text-blueGray-400 text-sm">{{ \Carbon\Carbon::parse($post->published_at)->format('j F, Y') }}</span>
                        </span>
                        <h2 class="text-4xl md:text-5xl mt-4 text-blue-800 font-bold font-heading">{{ $post->title }}</h2>
                    </div>
                    <div class="flex justify-center items-center mb-8">
                        <img class="w-16 h-16 object-cover rounded-full" src={{asset('storage/'.$post->author['image_url']) }} alt={{  $post->author['name'] }} />
                        <div class="pl-4">
                            <p class="text-blueGray-500 mb-1">{{ $post->author['name'] }}</p>
                            <p class="text-xs text-blueGray-400 font-semibold">Author</p>
                        </div>
                    </div>

                </div>
                <div class="social-container">
                         <div class="social-share">{!! $post->share_buttons !!}</div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="max-w-2xl mx-auto markdown-content porse">

                @markdown($post->content)
                <!-- {!! \Illuminate\Mail\Markdown::parse($post->content) !!} -->

              
                <!--Comment section-->
                <!-- <div class="antialiased mx-auto max-w-screen-sm">
                            <h3 class="mb-6 text-3xl font-semibold text-gray-900">Comments</h3>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" src="assets/imgs/placeholders/avatar-2.png" alt="" />
                                    </div>
                                    <div class="flex-1 border border-gray-100 rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                                        <strong>Kolawole</strong>
                                        <span class="text-xs text-gray-400">3:34 PM</span>
                                        <p class="text-sm">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                                        <div class="mt-4 flex items-center">
                                            <div class="flex -space-x-2 mr-2">
                                                <img class="rounded-full w-6 h-6 border border-white" src="assets/imgs/placeholders/avatar-3.png" alt="" />
                                                <img class="rounded-full w-6 h-6 border border-white" src="assets/imgs/placeholders/avatar-4.png" alt="" />
                                            </div>
                                            <div class="text-sm text-gray-500 font-semibold">5 Replies</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" src="assets/imgs/placeholders/avatar-5.png" alt="" />
                                    </div>
                                    <div class="flex-1 border border-gray-100 rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                                        <strong>Fulton</strong>
                                        <span class="text-xs text-gray-400">3:34 PM</span>
                                        <p class="text-sm">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                                        <h4 class="my-5 uppercase tracking-wide text-gray-400 font-bold text-xs">Replies</h4>
                                        <div class="space-y-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0 mr-3">
                                                    <img class="mt-3 rounded-full w-6 h-6 sm:w-8 sm:h-8" src="assets/imgs/placeholders/avatar-6.png" alt="" />
                                                </div>
                                                <div class="flex-1 bg-gray-50 rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                                                    <strong>Clara </strong>
                                                    <span class="text-xs text-gray-400">3:34 PM</span>
                                                    <p class="text-xs sm:text-sm">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                                                </div>
                                            </div>
                                            <div class="flex">
                                                <div class="flex-shrink-0 mr-3">
                                                    <img class="mt-3 rounded-full w-6 h-6 sm:w-8 sm:h-8" src="assets/imgs/placeholders/avatar-7.png" alt="" />
                                                </div>
                                                <div class="flex-1 bg-gray-50 rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                                                    <strong>Dany </strong>
                                                    <span class="text-xs text-gray-400">3:34 PM</span>
                                                    <p class="text-xs sm:text-sm">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                <!-- comment form -->
                <!-- <div class="mb-4 mt-12">
                            <form class="w-full max-w-xl bg-white rounded-lg">
                                <div class="flex flex-wrap mb-6">
                                    <h2 class="pt-3 pb-2 text-gray-800 text-lg font-bold">Add a new comment</h2>
                                    <div class="w-full md:w-full mb-2 mt-2">
                                        <textarea class="bg-gray-100 rounded border border-gray-100 leading-normal resize-none w-full h-32 py-4 px-3 focus:outline-none focus:bg-white text-sm" name="body" placeholder="Type Your Comment" required></textarea>
                                    </div>
                                    <div class="w-full md:w-full flex items-start md:w-full px-1">
                                        <div class="flex items-start w-1/2 text-gray-700 px-2 mr-auto">
                                            <svg fill="none" class="w-5 h-5 text-gray-600 mr-1" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-xs md:text-xs pt-px">Some HTML is okay.</p>
                                        </div>
                                    </div>
                                    <button class="transition duration-300 ease-in-out transform hover:-translate-y-1 block p-4 text-center text-xs text-white font-semibold leading-none bg-blue-800 hover:bg-blue-700 rounded mt-6">Post Comment</button>
                                </div>
                            </form>
                            
                        </div> -->

                <div class="transition duration-300 ease-in-out transform hover:-translate-y-1 flex items-center justify-center mt-12">
                    <a href="/posts" class="px-4 py-2 mt-2 text-gray-600 transition-colors duration-200 transform border border-gray-100 rounded-lg dark:text-gray-200 dark:border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"> Read More Articles </a>
                </div>
            </div>

        </div>
    </section>
</x-layout>