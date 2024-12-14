<x-app-layout>
<div x-data="{ 
    showModal: false, 
    selectedSkillId: null, 
    showGroupCourseModal: false, 
    errorMessage: '', 
    successMessage: '', 
    validateFileType(files) {
        if (files.length === 0) {
            this.errorMessage = 'Please upload at least one file.';
            this.successMessage = '';
            return false;
        }
        const allowedExtensions = ['pdf', 'png', 'jpeg', 'jpg'];
        for (let file of files) {
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                this.errorMessage = 'Please upload valid files.';
                this.successMessage = '';
                return false;
            }
        }
        this.errorMessage = ''; // Clear error if all validations pass
        return true;
    },
    handleSuccess() {
        this.successMessage = 'File uploaded successfully!';
        this.errorMessage = '';
        this.showModal = false;

        // Auto-hide the success message after 3 seconds
        setTimeout(() => {
            this.successMessage = '';
            window.location.reload(); // Refresh the page
        }, 3000);
    }
}">
<!-- Header with Button -->
        <x-slot name="header">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Teach') }}
            </h2>
           
        </x-slot>

        
        <!-- Message Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div x-show="errorMessage || successMessage" class="bg-red-200 p-4 rounded shadow-md mb-4 text-center">
                <p x-text="errorMessage" class="text-red-800 font-semibold"></p>
                <p x-text="successMessage" class="text-green-800 font-semibold"></p>
            </div>
        </div>

        <!-- Skills and Demand Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Skills and Demand</h2>

                <!-- Available Skills Table -->
                <h3 class="text-lg font-medium mb-4">Available Skills</h3>
                <h5 class="text-lg font-medium mb-4 bg-red-200">If interested in teaching, please upload certificates received from the relevant platform as proof</h5>
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 border-b font-medium text-left">Skill Name</th>
                            <th class="py-3 px-4 border-b font-medium text-left">Skill Description</th>
                            <th class="py-3 px-4 border-b font-medium text-left">Demand</th>
                            <th class="py-3 px-4 border-b font-medium text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $skill)
                            @php
                                // Calculate demand for each level dynamically
                                $levelDemand = [
                                    'L4' => $skill->users->where('level', 'L4')->count(),
                                    'L5' => $skill->users->where('level', 'L5')->count(),
                                    'L6' => $skill->users->where('level', 'L6')->count(),
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b">{{ $skill->name }}</td>
                                <td class="py-3 px-4 border-b">{{ $skill->description }}</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="block">L4: {{ $levelDemand['L4'] }}</span>
                                    <span class="block">L5: {{ $levelDemand['L5'] }}</span>
                                    <span class="block">L6: {{ $levelDemand['L6'] }}</span>
                                </td>
                                <td class="py-3 px-4 border-b">
                                    <!-- Pass the selected skill ID -->
                                    <button @click="showModal = true; selectedSkillId = {{ $skill->id }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        Teach Skill
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        

        <!-- Modal for Proof Upload -->
        <div x-show="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Upload Proof for Skill</h2>
                <form method="POST" action="{{ route('submit.skill.request') }}" enctype="multipart/form-data" 
                      @submit.prevent="if (validateFileType($refs.files.files)) { handleSuccess(); $el.submit(); } else { errorMessage = 'Please upload a valid file.' }">
                    @csrf
                    <div class="mb-6">
                        <label for="proof" class="block text-sm font-medium text-gray-700">Proof Files</label>
                        <input type="file" name="proof[]" id="proof" class="mt-2 block w-full border border-gray-300 rounded-md p-2" multiple x-ref="files">
                    </div>
                    <!-- Pass selected skill ID as hidden input -->
                    <input type="hidden" name="skill_id" :value="selectedSkillId">
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

      <!-- Approved Skills Section -->
<div 
    class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 "
    x-data="{ showUserModal: false, students: [], fetchStudents(skillId) {
        // Show the modal
        this.showUserModal = true;

        // Fetch students from the server
        fetch(`/students/${skillId}`)
            .then((response) => response.json())
            .then((data) => {
                this.students = data; // Update students array with response data
            })
            .catch((error) => {
                console.error('Error fetching students:', error);
                this.students = []; // Fallback to empty if there's an error
            });
    }}"
>
    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Approved Skills</h2>
        
        @if($approvedSkills->isEmpty())
            <p class="text-gray-600">You have no approved skills to teach yet.</p>
        @else
            <ul class="list-disc pl-6">
                @foreach($approvedSkills as $skill)
                    <li class="text-gray-600 mb-4">
                        <div class="flex justify-between items-center">
                            <!-- Skill Name -->
                            <span>{{ $skill->name }}</span>

                            <!-- One-to-One and Group Access Buttons -->
                            <div class="flex space-x-4">
                                <!-- One-to-One -->
                                <button 
                                    @click="fetchStudents({{ $skill->id }})" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Access
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div><!-- Rejected Skills Section -->
<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 mt-8">
    <h2 class="text-2xl font-semibold mb-6">Rejected Skills</h2>

    @if($rejectedSkills->isEmpty())
        <p class="text-gray-600">You have no rejected skills at the moment.</p>
    @else
        <ul class="list-disc pl-6">
            @foreach($rejectedSkills as $skill)
                <li class="text-gray-600 mb-4">
                    <div class="flex justify-between items-center">
                        <!-- Skill Name and Description -->
                        <div>
                            <strong>{{ $skill->skill->name }}</strong>
                            <p class="text-sm text-gray-500">{{ $skill->skill->description }}</p>
                        </div>

                        <!-- Rejection Reason -->
                        <div class="text-right">
                            <span class="text-red-600 italic text-sm font-medium">
                                Reason: {{ $skill->rejection_reason }}
                            </span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>


    

    <!-- Modal for Student Details -->
    <div x-show="showUserModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4">Students Interested in this Skill</h3>
            <template x-if="students.length > 0">
                <ul>
                    <template x-for="student in students" :key="student.id">
                        <li class="mb-2 text-gray-700">
                            <strong>Name:</strong> <span x-text="student.name"></span><br>
                            <strong>Level:</strong> <span x-text="student.level"></span>
                        </li>
                    </template>
                </ul>
            </template>
            <p x-show="students.length === 0" class="text-gray-600">No students found for this skill.</p>
            <div class="flex justify-end">
                <button @click="showUserModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>

<!-- Rejected Session Requests Section -->
<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 mt-8">
    <h2 class="text-2xl font-semibold mb-4">Rejected Session Requests</h2>

    @if($sessionRequests->where('status', 'rejected')->isEmpty())
        <p class="text-gray-600">No rejected session requests found.</p>
    @else
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 border-b font-medium text-left">Requester</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Level</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Skill</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Rejection Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessionRequests->where('status', 'rejected') as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b">{{ $request->user->name }}</td>
                        <td class="py-3 px-4 border-b">{{ $request->user->level }}</td>
                        <td class="py-3 px-4 border-b">{{ $request->skill->name }}</td>
                        <td class="py-3 px-4 border-b text-red-600 italic">{{ $request->rejection_reason }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>



        <!-- Session Requests Section -->
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 mt-8" x-data="{ showRejectModal: false, selectedRequestId: null }">
            <h2 class="text-2xl font-semibold mb-4">Pending Session Requests</h2>
            @if($sessionRequests->where('status', 'pending')->isEmpty())
                <p class="text-gray-600">No pending session requests found.</p>
            @else
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 border-b font-medium text-left">Requester</th>
                            <th class="py-3 px-4 border-b font-medium text-left">Skill</th>
                            <th class="py-3 px-4 border-b font-medium text-left">Status</th>
                            <th class="py-3 px-4 border-b font-medium text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessionRequests->where('status', 'pending') as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b">{{ $request->user->name }}</td>
                                <td class="py-3 px-4 border-b">{{ $request->skill->name }}</td>
                                <td class="py-3 px-4 border-b">{{ ucfirst($request->status) }}</td>
                                <td class="py-3 px-4 border-b">
                                    <!-- Show Accept and Reject Buttons only if the request is pending -->
                                   <!-- Accept Button -->
<form action="{{ route('session-request.update', $request->id) }}" method="POST" class="inline-block">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="accepted">
    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600">
        Accept
    </button>
</form>

<!-- Reject Button -->
<button 
    class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600"
    @click="showRejectModal = true; selectedRequestId = {{ $request->id }}"
>
    Reject
</button>
<div x-show="showRejectModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Rejection Reason</h2>
        <form :action="'{{ url('session-request/update') }}/' + selectedRequestId" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <div class="mb-6">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                <textarea 
                    name="rejection_reason" 
                    id="rejection_reason" 
                    class="mt-2 block w-full border border-gray-300 rounded-md p-2" 
                    required
                ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button 
                    type="button" 
                    @click="showRejectModal = false" 
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
                >
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
            <!-- Accepted Session Requests Section -->
<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 mt-8">
    <h2 class="text-2xl font-semibold mb-4">Accepted Session Requests</h2>
    @if($sessionRequests->where('status', 'accepted')->isEmpty())
        <p class="text-gray-600">No accepted session requests found.</p>
    @else
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 border-b font-medium text-left">Requester</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Skill</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Level</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Status</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Time</th>
                    <th class="py-3 px-4 border-b font-medium text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessionRequests->where('status', 'accepted') as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b">{{ $request->user->name }}</td>
                        <td class="py-3 px-4 border-b">{{ $request->skill->name }}</td>
                        <td class="py-3 px-4 border-b">{{ $request->level ?? 'N/A' }}</td> <!-- Added Level -->
                        <td class="py-3 px-4 border-b">{{ ucfirst($request->status) }}</td>
                        <td class="py-3 px-4 border-b">08:00:00</td> <!-- Hardcoded Time -->
                        <td class="py-3 px-4 border-b">
                            <a href="{{ route('sessions.join', $request->id) }}" 
                               class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 inline-block">
                                Join Session
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


    </div>
</x-app-layout>
