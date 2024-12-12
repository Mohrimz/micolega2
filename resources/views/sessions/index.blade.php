<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accepted Sessions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($acceptedSessions->isEmpty())
                    <p class="text-gray-600">You have no accepted sessions at the moment.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($acceptedSessions as $session)
                        <div class="p-4 border border-gray-300 rounded-lg shadow-sm bg-white">
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ $session->skill->name }}</h4>
                            <p class="text-gray-700"><strong>Tutor:</strong> {{ $session->tutor->name }}</p>
                            <p class="text-gray-700"><strong>Time:</strong> {{ $session->created_at->format('Y-m-d H:i:s') }}</p>
                            <a href="{{ route('student.sessions.join', $session->id) }}" 
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
