<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Join Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Session: {{ $groupCourse->skill->name ?? 'Skill Not Found' }}</h1>
                <p><strong>Tutor:</strong> {{ $groupCourse->creator->name ?? 'Unknown' }}</p>
                <p><strong>Level:</strong> {{ $groupCourse->level }}</p>
                <p><strong>Date:</strong> {{ $groupCourse->date }}</p>
                <p><strong>Time:</strong> {{ $groupCourse->time }}</p>

                <div class="mt-6">
                    <p class="text-lg font-semibold">Join the Session:</p>
                    <iframe src="https://meet.jit.si/{{ $groupCourse->id }}-{{ $groupCourse->created_by }}" width="100%" height="600" class="mt-4"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
