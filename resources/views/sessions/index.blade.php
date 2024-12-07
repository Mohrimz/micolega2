<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Approved Sessions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6">My Approved Sessions</h3>

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                @if($approvedSessions->isEmpty())
                    <p class="text-gray-600">No approved sessions found.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($approvedSessions as $session)
                        <div class="p-4 border border-gray-300 rounded-lg shadow-sm bg-white">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $session->skill->name }}</h4>
                            <p class="text-gray-700"><strong>Tutor:</strong> {{ $session->tutor->name }}</p>
                            <p class="text-gray-700"><strong>Student:</strong> {{ $session->user->name }}</p>
                            <p class="text-gray-700"><strong>Time:</strong> {{ $session->created_at->format('Y-m-d H:i:s') }}</p>

                            <div class="flex flex-col mt-4 space-y-2">
                                <a href="{{ route('sessions.join', $session->id) }}" 
                                   class="bg-green-500 text-white px-4 py-2 rounded-md text-center hover:bg-green-600">
                                    Join Session
                                </a>
                                <form action="{{ route('sessions.cancel', $session->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Cancel Session
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
