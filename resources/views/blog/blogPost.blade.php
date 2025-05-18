<x-layout>
<section class="py-20">

    
				<div class="container">
					<h2 class="text-3xl lg:text-4xl mb-8 font-bold font-heading">Our Blog</h2>
					<div class="flex flex-wrap -mx-3">
						
						@foreach ($posts as $post)
						<div class="w-full lg:w-1/3 px-3 mb-12 wow animate__animated animate__fadeInUp animated hover-up-5" data-wow-delay=".1s">
							<a href="{{'posts/'. $post->slug }}">
								<img class="h-80 w-full object-cover rounded" src="{{asset('storage/'.$post->image) }}" alt="{{   $post->title }}">
								<p class="mt-6 text-sm text-blue-400">
									@foreach ($post->categories as $category )
										<span class="inline-block py-1 px-3 text-xs font-semibold bg-blue-100 text-blue-600 rounded-xl mr-3">{{ $category->name}}</span>
									@endforeach
									
									<span class="text-blueGray-400 text-xs">{{ \Carbon\Carbon::parse($post->published_at)->format('j F, Y') }}</span>
								</p>
								<h3 class="my-2 text-2xl font-bold font-heading">{{ $post->title }}</h3>
							</a>
						</div>
						@endforeach
						
					</div>
					<div class="text-center">
						<a class="hover-up-5 inline-block py-4 px-8 text-xs text-white font-semibold leading-none bg-blue-400 hover:bg-blue-500 rounded" href="#">Show all posts</a>
					</div>
					
				</div>
			</section>
</x-layout>