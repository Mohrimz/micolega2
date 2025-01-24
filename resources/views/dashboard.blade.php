<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if(auth()->user()->hasRole('admin'))
<!-- Admin Section -->
<div class="mb-6" x-data="{ showRejectModal: false, showAcceptModal: false, rejectionReason: '', acceptNotes: '', selectedDocumentId: null }">
    <h3 class="text-2xl font-semibold">Pending Requests for Tutoring:</h3>
    @foreach ($proofDocuments->where('status', 'pending') as $document)
    <div class="mb-6 p-4 border border-gray-300 rounded-lg shadow-sm">
        <p><strong>Skill:</strong> {{ $document->skill->name }}</p>
        <p><strong>User:</strong> {{ $document->user->name }}</p>
        <p><strong>Document:</strong>
            <a href="{{ route('admin.view.document', $document->id) }}" target="_blank" class="text-blue-500 hover:underline">
                View Document
            </a>
        </p>

        <div class="flex items-center space-x-4 mt-4">
            <button 
                @click="showAcceptModal = true; selectedDocumentId = {{ $document->id }};" 
                class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                Approve with Notes
            </button>

            <button 
                @click="showRejectModal = true; selectedDocumentId = {{ $document->id }};" 
                class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                Reject
            </button>
        </div>
    </div>
    @endforeach

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Rejection Modal -->
    <div x-show="showRejectModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Rejection Reason</h2>
            <form method="POST" action="{{ route('admin.proof.reject') }}">
                @csrf
                <input type="hidden" name="document_id" :value="selectedDocumentId">
                <div class="mb-6">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                    <textarea name="rejection_reason" id="rejection_reason" x-model="rejectionReason" class="mt-2 block w-full border border-gray-300 rounded-md p-2" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showRejectModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Accept Modal -->
    <div x-show="showAcceptModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Approval Notes</h2>
            <form method="POST" action="{{ route('admin.proof.accept') }}">
                @csrf
                <input type="hidden" name="document_id" :value="selectedDocumentId">
                <div class="mb-6">
                    <label for="accept_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="accept_notes" id="accept_notes" x-model="acceptNotes" class="mt-2 block w-full border border-gray-300 rounded-md p-2" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showAcceptModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    



                <!-- Available Skills Section -->
                <div class="mt-6">
                    <h3 class="text-xl font-semibold mb-4">Available Skills:</h3>
                    <table class="min-w-full bg-white border border-gray-300 shadow-sm rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left">Name</th>
                                <th class="px-6 py-4 text-left">Description</th>
                                <th class="px-6 py-4 text-left">Category</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skills as $skill)
                                <tr class="border-t">
                                    <td class="px-6 py-4">{{ $skill->name }}</td>
                                    <td class="px-6 py-4">{{ $skill->description }}</td>
                                    <td class="px-6 py-4">{{ $skill->category->category_name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('admin.skills.destroy', $skill->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Add New Skill Form -->
                <div class="mt-6">
                    <h3 class="text-xl font-semibold mb-4">Add a New Skill:</h3>
                    <form method="POST" action="{{ route('admin.skills.store') }}" class="space-y-4">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Skill Name:</label>
                            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md p-3" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description:</label>
                            <textarea name="description" id="description" class="w-full border-gray-300 rounded-md p-3" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-gray-700">Category:</label>
                            @foreach($categories as $category)
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="category_id" value="{{ $category->id }}" class="form-radio" required>
                                    <label class="text-gray-700">{{ $category->category_name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Skill</button>
                    </form>
                </div>

                @else
                
  <!-- Tutors Recommendation Section -->
<div x-data="{ showSuccess: false, successMessage: '' }" class="mt-6">
    <h3 class="text-2xl font-semibold mb-6">Recommended Tutors for You:</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tutors as $tutor)
        <div class="p-4 border border-gray-300 rounded-lg shadow-sm bg-white">
            <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ $tutor->name }}</h4>
            <p class="text-gray-700"><strong>Email:</strong> {{ $tutor->email }}</p>

            <form action="{{ route('tutors.requestSession') }}" method="POST" class="mt-4" 
                  @submit.prevent="
                    successMessage = 'Your session request has been successfully submitted!';
                    showSuccess = true;
                    setTimeout(() => { showSuccess = false; }, 10000); // Hide after 5 seconds
                    $el.submit();
            ">
                @csrf
                <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

                <!-- Select Skill -->
                <label for="skill_id_{{ $tutor->id }}" class="block text-gray-700">Select Skill:</label>
                <select name="skill_id" id="skill_id_{{ $tutor->id }}" class="border rounded p-3 w-full">
                    @foreach($skills as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                    @endforeach
                </select>

                <!-- Select Availability -->
                <label for="availability_id_{{ $tutor->id }}" class="block text-gray-700 mt-4">Select Available Slot:</label>
                <select name="availability_id" id="availability_id_{{ $tutor->id }}" class="border rounded p-3 w-full">
                    @foreach($tutor->availabilities as $availability)
                    <option value="{{ $availability->id }}">{{ $availability->date }} at {{ $availability->time }}</option>
                    @endforeach
                </select>

                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full">
                    Request Session
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Success Message Modal -->
    <div x-show="showSuccess" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50" x-transition>
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md text-center">
            <h2 class="text-xl font-bold mb-4 text-green-600" x-text="successMessage"></h2>
            <button @click="showSuccess = false" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Close
            </button>
        </div>
    </div>
</div>

                @endif
            </div>
        </div>
    </div>
</x-app-layout>
