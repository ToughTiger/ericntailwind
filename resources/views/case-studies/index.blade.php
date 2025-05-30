<x-layout>
<section class="py-20">
				<div class="container">
					<h2 class="text-3xl lg:text-4xl mb-8 font-bold font-heading">Case Studies</h2>
					<div class="flex flex-wrap -mx-3">						
						@foreach ( $caseStudies as $caseStudy )						
						<div class="w-full lg:w-1/3 p-3 mb-12 wow animate__animated animate__fadeInUp animated hover-up-5 bg-blue-100 rounded" data-wow-delay=".1s">
							<a href="{{ route('case-studies.show', $caseStudy->slug) }}">
								<img class="h-60 w-full object-cover rounded" src="{{ $caseStudy->thumbnail_url }}" alt="{{ $caseStudy->title }}">
								<p class="mt-6 text-sm text-blue-400">
									<span class="text-blueGray-400 text-xs">{{\Carbon\Carbon::parse($caseStudy->created_at)->format('j F, Y') }}</span>
								</p>
								<h3 class="my-2 text-2xl font-bold font-heading">{{ $caseStudy->title }}</h3>
								<div class="markdown-content">
									{{ $caseStudy->description }}
</div>
							</a>
						</div>
						@endforeach
					
				</div>
				<div class="text-center">
						<a class="hover-up-5 inline-block py-4 px-8 text-xs text-white font-semibold leading-none bg-blue-400 hover:bg-blue-500 rounded" href="#">Show all posts</a>
					</div>
			</section>
</x-layout>