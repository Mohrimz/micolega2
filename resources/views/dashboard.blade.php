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

                    <div class="mb-6">
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

                            <form action="{{ route('admin.proof.update', $document->id) }}" method="POST" class="mt-4">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center space-x-4">
                                    <select name="status" class="border rounded-lg p-2 w-40">
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Update Status</button>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    </div>

                    @if(session('success'))
                    <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif

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
                    <p>You do not have permission to view this page.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
