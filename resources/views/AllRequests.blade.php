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
                    <h2>Welcome Admin</h2>
                    <div>
                        <h3>Pending documents:</h3>
                        @foreach ($proofDocuments->where('status', 'pending') as $document)
                        <div class="mb-4 p-4 border rounded">
                            <p><strong>Skill:</strong> {{ $document->skill->name }}</p>
                            <p><strong>User:</strong> {{ $document->user->name }}</p>
                            <p><strong>Document:</strong>
                                <a href="{{ Storage::url($document->document_path) }}" target="_blank">View Document</a>
                            </p>
                            <form action="{{ route('admin.proof.update', $document->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="border rounded p-2">
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <button type="submit" class="bg-blue-500  px-4 py-2 rounded">Update Status</button>
                            </form>
                        </div>
                    @endforeach
                    <h3>Accepted documents:</h3>
                    @foreach ($proofDocuments->where('status', 'accepted') as $document)
                        <div class="mb-4 p-4 border rounded">
                            <p><strong>Skill:</strong> {{ $document->skill->name }}</p>
                            <p><strong>User:</strong> {{ $document->user->name }}</p>
                            <p><strong>Document:</strong>
                                <a href="{{ Storage::url($document->document_path) }}" target="_blank">View Document</a>
                            </p>
                            <form action="{{ route('admin.proof.update', $document->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="border rounded p-2">
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <button type="submit" class="bg-blue-500  px-4 py-2 rounded">Update Status</button>
                            </form>
                        </div>
                    @endforeach
                    <h3>Rejected documents:</h3>
                    @foreach ($proofDocuments->where('status', 'rejected') as $document)
                        <div class="mb-4 p-4 border rounded">
                            <p><strong>Skill:</strong> {{ $document->skill->name }}</p>
                            <p><strong>User:</strong> {{ $document->user->name }}</p>
                            <p><strong>Document:</strong>
                                <a href="{{ Storage::url($document->document_path) }}" target="_blank">View Document</a>
                            </p>
                            <form action="{{ route('admin.proof.update', $document->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="border rounded p-2">
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <button type="submit" class="bg-blue-500  px-4 py-2 rounded">Update Status</button>
                            </form>
                        </div>
                    @endforeach
                       
                    </div>
                    

                    
            </div>
        </div>
    </div>
    
    
 
             
                    
                  
                 

  
</x-app-layout>
