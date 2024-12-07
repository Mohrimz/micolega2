<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Join Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Session: {{ $sessionRequest->skill->name }}</h1>
                <p><strong>Tutor:</strong> {{ $sessionRequest->tutor->name }}</p>
                <p><strong>Student:</strong> {{ $sessionRequest->user->name }}</p>
                <p><strong>Time:</strong> {{ $sessionRequest->created_at->format('Y-m-d H:i:s') }}</p>

                <div class="mt-6">
                    <p class="text-lg font-semibold">Join the Session:</p>
                    <iframe src="https://meet.jit.si/{{ $sessionRequest->id }}" width="100%" height="600" class="mt-4"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
