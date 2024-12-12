<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accepted Group Sessions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($acceptedSessions->isEmpty())
                    <p class="text-gray-600 text-center">No accepted group sessions available at the moment.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($acceptedSessions as $session)
                            <div class="p-6 border border-gray-300 rounded-lg shadow-md bg-gray-50 hover:bg-gray-100 transition duration-200">
                                <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ $session->skill->name }}</h4>
                                <p class="text-gray-700"><strong>Created By:</strong> {{ $session->creator->name }}</p>
                                <p class="text-gray-700"><strong>Level:</strong> {{ $session->level }}</p>
                                <p class="text-gray-700"><strong>Date:</strong> {{ $session->date }}</p>
                                <p class="text-gray-700"><strong>Time:</strong> {{ $session->time }}</p>
                                <a href="#" 
                                   class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full text-center inline-block">
                                    Join Session
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
