<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Skills') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Pending Skills</h3>

                @if($skills->isEmpty())
                    <p class="text-gray-600">No pending skills.</p>
                @else
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Skill Name</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skills as $skill)
                                <tr>
                                    <td>{{ $skill->name }}</td>
                                    <td class="text-center">
                                        <!-- Accept Button -->
                                        <button 
                                            class="btn btn-success btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#acceptModal" 
                                            data-skill-id="{{ $skill->id }}" 
                                            data-skill-name="{{ $skill->name }}">
                                            Accept
                                        </button>

                                        <!-- Reject Button -->
                                        <form method="POST" action="{{ route('skill.reject') }}" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Accept Modal -->
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('skill.accept') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="acceptModalLabel">Add Description</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="skill_id" name="skill_id">
                        <div class="mb-3">
                            <label for="skill_name" class="form-label">Skill Name</label>
                            <input type="text" id="skill_name" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const acceptModal = document.getElementById('acceptModal');
        acceptModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const skillId = button.getAttribute('data-skill-id');
            const skillName = button.getAttribute('data-skill-name');

            const modalSkillId = acceptModal.querySelector('#skill_id');
            const modalSkillName = acceptModal.querySelector('#skill_name');

            modalSkillId.value = skillId;
            modalSkillName.value = skillName;
        });
    </script>
</x-app-layout>
