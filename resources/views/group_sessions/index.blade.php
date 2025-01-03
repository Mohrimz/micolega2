<x-app-layout>
    <div x-data="{ showModal: false }" class="min-h-screen bg-gray-100">
        <!-- Header Section with Title, Filter, and Button -->
        <div class="flex justify-between items-center px-6 py-4 bg-white shadow-md">
            <h1 class="text-2xl font-bold text-gray-800">Group Sessions</h1>
            <div class="flex items-center space-x-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('group-sessions') }}" class="flex space-x-4">
    <!-- Skill Dropdown -->
    <div>
        <label for="skill_id" class="sr-only">Skill</label>
        <select name="skill_id" id="skill_id" class="border-gray-300 rounded-md p-2">
            <option value="">All Skills</option>
            @foreach($skills as $skill)
                <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>
                    {{ $skill->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Level Dropdown -->
    <div>
        <label for="level" class="sr-only">Level</label>
        <select name="level" id="level" class="border-gray-300 rounded-md p-2">
            <option value="">All Levels</option>
            <option value="L4" {{ request('level') == 'L4' ? 'selected' : '' }}>L4</option>
            <option value="L5" {{ request('level') == 'L5' ? 'selected' : '' }}>L5</option>
            <option value="L6" {{ request('level') == 'L6' ? 'selected' : '' }}>L6</option>
        </select>
    </div>

    <!-- Search Button -->
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
        Search
    </button>
</form>

                <!-- Create Group Course Button -->
                <button 
                    @click="showModal = true" 
                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Create Group Course
                </button>
            </div>
        </div>

        <!-- Courses List -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Available Group Courses</h1>

            @if($groupCourses->isEmpty())
                <p class="text-gray-600 text-center">No group courses available for the selected filters.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groupCourses as $course)
                        <div class="p-4 border border-gray-300 rounded-lg shadow-md bg-gray-50 hover:bg-gray-100 transition duration-200">
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ $course->skill->name ?? 'Skill Not Found' }}</h4>
                            <p class="text-gray-700"><strong>Tutor:</strong> {{ $course->creator->name ?? 'Unknown' }}</p>
                            <p class="text-gray-700"><strong>Level:</strong> {{ $course->level }}</p>
                            <p class="text-gray-700"><strong>Date:</strong> {{ $course->date }}</p>
                            <p class="text-gray-700"><strong>Time:</strong> {{ $course->time }}</p>

                            <!-- Enrollment Count for Tutors -->
                            @if(auth()->id() === $course->creator->id)
                                <p class="text-gray-700 mt-4"><strong>Enrollment:</strong> {{ $course->users->count() }}</p>
                            @endif

                            <!-- Enroll/Exclude Button for Students -->
                            @if(auth()->id() !== $course->creator->id)
                                <form action="{{ route('enroll.session', $course->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    @if($course->users->contains(auth()->id()))
                                        <button type="submit" 
                                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 w-full">
                                            Exclude
                                        </button>
                                    @else
                                        <button type="submit" 
                                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full">
                                            Enroll
                                        </button>
                                    @endif
                                </form>
                            @endif

                            <!-- Join Session Button -->
                            @if(auth()->id() === $course->creator->id || $course->users->contains(auth()->id()))
                                <a href="{{ route('join.session', $course->id) }}" 
                                   class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 w-full text-center inline-block">
                                    Join Session
                                </a>
                            @else
                                <button disabled
                                        class="mt-4 bg-gray-500 text-white px-4 py-2 rounded-md cursor-not-allowed w-full text-center inline-block">
                                    Join Session
                                </button>
                            @endif

                            <!-- Cancel Session Button (Modal Trigger for Creator Only) -->
                            @if(auth()->id() === $course->creator->id)
                                <div x-data="{ showModal: false, rejectReason: '' }">
                                    <button @click="showModal = true" 
                                            class="mt-2 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 w-full">
                                        Remove Session
                                    </button>

                                    <!-- Modal -->
                                    <div x-show="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                                            <h2 class="text-xl font-bold mb-4">Enter Rejection Reason</h2>
                                            <form method="POST" action="{{ route('remove.session', $course->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="mb-4">
                                                    <label for="reject_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                                    <textarea name="reject_reason" id="reject_reason" rows="3" required
                                                              x-model="rejectReason"
                                                              class="mt-2 p-2 w-full border border-gray-300 rounded-md"></textarea>
                                                </div>
                                                <div class="flex justify-end space-x-4">
                                                    <button type="button" 
                                                            @click="showModal = false" 
                                                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" 
                                                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
                                                            :disabled="!rejectReason">
                                                        Confirm
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>



        <!-- Student Availability -->
<!-- Student Availability Section -->
<div class="container mx-auto mt-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Student Availability</h2>
    @if($studentAvailabilities->isEmpty())
        <p class="text-gray-600 text-center">No student availabilities found.</p>
    @else
        <table class="table-auto w-full border border-gray-300 rounded-lg shadow-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Skill</th>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Time</th>
                    <th class="px-4 py-2 border">Student Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentAvailabilities as $availability)
                    <tr>
                        <td class="px-4 py-2 border">{{ $availability->skill_name }}</td>
                        <td class="px-4 py-2 border">{{ $availability->date }}</td>
                        <td class="px-4 py-2 border">{{ $availability->time }}</td>
                        <td class="px-4 py-2 border">{{ $availability->student_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


        <!-- Modal for Create Group Course -->
        <div 
            x-show="showModal" 
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
            x-cloak>
            <div class="bg-white p-8 rounded-lg w-full max-w-md shadow-lg">
                <h2 class="text-xl font-bold mb-4">Create Group Course</h2>
                <form method="POST" action="{{ route('group-courses.store') }}">
                    @csrf
                    <!-- Skill Dropdown -->
<div class="mb-4">
    <label for="skill_id" class="block text-sm font-medium text-gray-700">Skill</label>
    <select name="skill_id" id="skill_id" class="w-full border-gray-300 rounded-md p-2 mt-1" required>
        @foreach($skills as $skill)
            @if(in_array($skill->id, $approvedSkillIds->toArray())) <!-- Ensure the skill is approved -->
                <option value="{{ $skill->id }}">
                    {{ $skill->name }}
                </option>
            @endif
        @endforeach
    </select>
</div>

                    <!-- Level -->
                    <div class="mb-4">
                        <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                        <select name="level" id="level" class="w-full border-gray-300 rounded-md p-2 mt-1" required>
                            <option value="L4">L4</option>
                            <option value="L5">L5</option>
                            <option value="L6">L6</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" 
                               class="w-full border-gray-300 rounded-md p-2 mt-1" required>
                    </div>

                    <!-- Time -->
                    <div class="mb-4">
                        <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                        <input type="time" name="time" id="time" 
                               class="w-full border-gray-300 rounded-md p-2 mt-1" required>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button 
                            type="button" 
                            @click="showModal = false" 
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
