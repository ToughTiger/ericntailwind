<x-layout>

    <section class="pb-20">
        <div class="pt-20 pb-8 mb-12 bg-cover bg-no-repeat">
            <div class="container">
                <div class="flex w-full">
                    <div class=" mb-6 w-1/2">
                        <h2 class="text-3xl md:text-4xl mt-4 text-blue-800 font-bold font-heading">{{ $caseStudy->title }}</h2>

                        <img class="h-48 w-48 object-cover rounded mx-auto" src="{{asset('storage/'.$caseStudy->thumbnail_path) }}" alt="{{  $caseStudy->title }}">

                        <h3 class="font-bold text-md pt-20 mx-auto px-8">Case Study will be automatically downloaded on submission of this form.</h3>

                    </div>
                    <div class=" mb-6  w-1/2">
                        <div class="relative flex flex-wrap">
                            <div class="lg:flex lg:flex-col w-full py-6 lg:pr-20">
                                <div class="w-full max-w-lg mx-auto lg:mx-0 my-auto wow animate__animated animate__fadeIn animated" data-wow-delay=".3s">
                                    <h4 class="mb-6 text-3xl">Download Case Study</h4>
                                     <form action="{{ route('case-studies.download', $caseStudy) }}" method="POST">
                                     @csrf
                                        <div class="flex mb-4 px-4 bg-blueGray-50 rounded border border-gray-200">
                                            <input class="w-full py-4 text-xs placeholder-blueGray-400 font-semibold leading-none bg-blueGray-50 outline-none" type="text" id="name" name="name" placeholder="Name" value="{{ old('name') }}" />
                                            <svg class="h-6 w-6 ml-4 my-auto text-blueGray-300" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 7C9.65685 7 11 5.65685 11 4C11 2.34315 9.65685 1 8 1C6.34315 1 5 2.34315 5 4C5 5.65685 6.34315 7 8 7Z" fill="#000000" />
                                                <path d="M14 12C14 10.3431 12.6569 9 11 9H5C3.34315 9 2 10.3431 2 12V15H14V12Z" fill="#000000" />
                                            </svg>
                                        </div>
                                        <div class="flex mb-4 px-4 bg-blueGray-50 rounded border border-gray-200">
                                            <input class="w-full py-4 text-xs placeholder-blueGray-400 font-semibold leading-none bg-blueGray-50 outline-none" type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" />


                                            <svg fill="#000000" class="h-6 w-6 ml-4 my-auto text-blueGray-300" viewBox="0 0 24 24" id="email-file-text" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                                                <path id="secondary" d="M5.29,4.29l2-2A1,1,0,0,1,8,2h9a2,2,0,0,1,2,2v7a1,1,0,0,1-.45.83l-5.44,3.63h0a2,2,0,0,1-2.22,0L5.45,11.83A1,1,0,0,1,5,11V5A1,1,0,0,1,5.29,4.29Z" style="fill: rgb(44, 169, 188);"></path>
                                                <path id="primary" d="M3.06,9.1a2,2,0,0,1,2.05.1L12,13.8l6.89-4.6A2,2,0,0,1,22,10.87V20a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V10.87A2,2,0,0,1,3.06,9.1ZM8,2a1,1,0,0,0-.71.28l-2,2A1,1,0,0,0,5,5H7A1,1,0,0,0,8,4Z" style="fill: rgb(0, 0, 0);"></path>
                                            </svg>
                                        </div>
                                        <div class="flex mb-6 px-4 bg-blueGray-50 rounded border border-gray-200">
                                            <input class="w-full py-4 text-xs placeholder-blueGray-400 font-semibold leading-none bg-blueGray-50 outline-none" type="text" id="phone" name="phone"placeholder="Contact Number" value="{{ old('phone') }}" />
                                            <button class="ml-4">
                                                <svg class="h-6 w-6 ml-4 my-auto text-blueGray-300" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="6.5" y="3" width="12" height="18" rx="3" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12.5 18.5C12.3665 18.5 12.2409 18.448 12.1465 18.3536C12.052 18.2591 12 18.1336 12 18C12 17.9008 12.0291 17.8047 12.0843 17.7222C12.1394 17.6397 12.217 17.576 12.3086 17.5381C12.3696 17.5128 12.434 17.5 12.5 17.5C12.5327 17.5 12.5655 17.5032 12.5975 17.5096C12.6949 17.529 12.7834 17.5763 12.8536 17.6464C12.9237 17.7166 12.971 17.8051 12.9904 17.9025C13.0098 17.9998 12.9999 18.0997 12.9619 18.1913C12.924 18.283 12.8603 18.3606 12.7778 18.4157C12.6953 18.4709 12.5992 18.5 12.5 18.5Z" fill="#000000" />
                                                    <path d="M12.5 19C12.2348 19 11.9804 18.8946 11.7929 18.7071C11.6054 18.5196 11.5 18.2652 11.5 18C11.5 17.8022 11.5586 17.6089 11.6685 17.4444C11.7784 17.28 11.9346 17.1518 12.1173 17.0761C12.3 17.0004 12.5011 16.9806 12.6951 17.0192C12.8891 17.0578 13.0673 17.153 13.2071 17.2929C13.347 17.4327 13.4422 17.6109 13.4808 17.8049C13.5194 17.9989 13.4996 18.2 13.4239 18.3827C13.3482 18.5654 13.22 18.7216 13.0556 18.8315C12.8911 18.9414 12.6978 19 12.5 19Z" fill="#000000" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="float-left mb-6 wow animate__animated animate__fadeIn animated" data-wow-delay=".1s">
                                            <label class="inline-flex text-xs">
                                                <input type="checkbox" class="form-checkbox" checked />
                                                <span class="ml-2">I agree to <a class="underline hover:text-blueGray-500" href="/gdpr">Privacy Policy</a></span>
                                            </label>
                                        </div>
                                        <input type="hidden" name="case_study_id" id="case_study_id" value="{{ $caseStudy->id }}">
                                        <button class="transition duration-300 ease-in-out transform hover:-translate-y-1 block w-full p-4 text-center text-xs text-white font-semibold leading-none bg-blue-500 hover:bg-blue-700 rounded">Download</button>
                                        <p class="my-6 text-xs text-blueGray-400 text-center font-semibold">By completing and submitting the case study form you’re confirming you’re happy for us to securely store your personal details so we can send you the case study.</p>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="text-center">
						<a class="hover-up-5 inline-block py-4 px-8 text-xs text-white font-semibold leading-none bg-blue-400 hover:bg-blue-500 rounded" href="/case-studies">Show all Case Studies</a>
					</div>
            </div>
        </div>


    </section>
</x-layout>