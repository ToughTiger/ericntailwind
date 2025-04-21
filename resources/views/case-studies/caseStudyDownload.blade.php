<x-layout>
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($caseStudy->thumbnail_path)
                <img src="{{ $caseStudy->thumbnail_url }}" alt="{{ $caseStudy->title }}" class="w-full h-64 object-cover">
            @endif
            
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4">{{ $caseStudy->title }}</h1>
                
                @if($caseStudy->description)
                    <div class="prose max-w-none mb-6">
                        {!! nl2br(e($caseStudy->description)) !!}
                    </div>
                @endif
                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4">Download this case study</h2>
                    <p class="mb-4">Please provide your details to download the PDF.</p>
                    
                    <form action="{{ route('case-studies.download', $caseStudy) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="tel" id="phone" name="phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                                <input type="text" id="company" name="company"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('company') }}">
                                @error('company')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Download Case Study
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>