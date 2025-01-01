<x-guest-layout>
    <x-slot name="logo">
        <img src="{{ asset('images/logoMiColega.jpeg') }}" alt="Logo" class="block mx-auto h-16 w-auto mb-6" />
    </x-slot>

    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md border border-gray-200 mt-10">
        <h1 class="text-xl font-bold text-center text-gray-800 mb-4">{{ __('Register') }}</h1>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block w-full mt-1 text-sm border-gray-300 focus:ring-indigo-500 rounded-md" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block w-full mt-1 text-sm border-gray-300 focus:ring-indigo-500 rounded-md" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block w-full mt-1 text-sm border-gray-300 focus:ring-indigo-500 rounded-md" 
                    type="password" 
                    name="password" 
                    required />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block w-full mt-1 text-sm border-gray-300 focus:ring-indigo-500 rounded-md" 
                    type="password" 
                    name="password_confirmation" 
                    required />
            </div>

            <!-- Level Selection -->
            <div class="mb-4">
                <x-label for="level" value="{{ __('Select Level') }}" />
                <select id="level" name="level" class="block w-full mt-1 text-sm border-gray-300 focus:ring-indigo-500 rounded-md" required>
                    <option value="">{{ __('Choose your level') }}</option>
                    <option value="L4" {{ old('level') === 'L4' ? 'selected' : '' }}>L4</option>
                    <option value="L5" {{ old('level') === 'L5' ? 'selected' : '' }}>L5</option>
                   
                </select>
            </div>

            <!-- Skills -->
<!-- Skills -->
<div class="mb-4">
    <label class="text-sm font-medium text-gray-700">{{ __('Select Skills and Preferences') }}</label>
    <div class="grid grid-cols-1 gap-4 mt-2">
        @foreach ($skills as $skill)
            <div class="flex flex-col gap-2">
                <!-- Skill Checkbox -->
                <label class="flex items-center text-sm">
                    <input type="checkbox" 
                           name="skills[{{ $skill->id }}][id]" 
                           value="{{ $skill->id }}" 
                           class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                           {{ old("skills.{$skill->id}.id") ? 'checked' : '' }}
                           onchange="togglePreferences(this, {{ $skill->id }})">
                    {{ $skill->name }}
                </label>

                <!-- Preferences Radio Buttons -->
                <div class="ml-6 space-y-2" id="preferences-{{ $skill->id }}" style="display: {{ old("skills.{$skill->id}.id") ? 'block' : 'none' }}">
                    @foreach ($preferences as $preference)
                        <label class="flex items-center text-sm">
                            <input type="radio" 
                                   name="skills[{{ $skill->id }}][preference_id]" 
                                   value="{{ $preference->id }}" 
                                   class="mr-2 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   {{ old("skills.{$skill->id}.preference_id") == $preference->id ? 'checked' : '' }}
                                   {{ !old("skills.{$skill->id}.id") ? 'disabled' : '' }}>
                            {{ $preference->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>


            <!-- Select Time -->
<div class="mb-4">
    <h2 class="text-sm font-medium text-gray-700 mb-2">{{ __('Select Available Times') }}</h2>
    <table class="min-w-full text-sm table-auto border border-gray-300 rounded-lg shadow-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-2 py-1 border border-gray-300">{{ __('Day') }}</th>
                @foreach($timeSlots as $timeSlot)
                    <th class="px-2 py-1 border border-gray-300">{{ $timeSlot }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($availabilities->groupBy('date') as $date => $availabilityGroup)
                <tr class="text-center">
                    <td class="px-2 py-1 border border-gray-300">{{ $date }}</td>
                    @foreach($timeSlots as $timeSlot)
                        @php
                            // Find the specific availability for this date and time
                            $matchingAvailability = $availabilityGroup->firstWhere('time', "$timeSlot:00");
                        @endphp
                        <td class="px-2 py-1 border border-gray-300">
                            @if($matchingAvailability)
                                <input 
                                    type="checkbox" 
                                    name="availabilities[]" 
                                    value="{{ $matchingAvailability->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                    {{ old('availabilities') && in_array($matchingAvailability->id, old('availabilities')) ? 'checked' : '' }}>
                            @else
                                <span class="text-gray-400">—</span> <!-- Placeholder for unavailable slots -->
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


            <!-- Terms and Privacy -->
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-4 text-sm">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <span class="ml-2 text-gray-600">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a href="'.route('terms.show').'" class="text-indigo-600 hover:underline">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a href="'.route('policy.show').'" class="text-indigo-600 hover:underline">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </span>
                        </div>
                    </x-label>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="mt-6">
                <x-button class="w-full px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                    {{ __('Register') }}
                </x-button>
            </div>

            <!-- Already Registered -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">{{ __('Already registered?') }}</a>
            </div>
        </form>
    </div>
    <script>
        function togglePreferences(checkbox, skillId) {
            const preferencesDiv = document.getElementById(`preferences-${skillId}`);
            const radios = preferencesDiv.querySelectorAll('input[type="radio"]');
            if (checkbox.checked) {
                preferencesDiv.style.display = 'block';
                radios.forEach(radio => radio.disabled = false);
            } else {
                preferencesDiv.style.display = 'none';
                radios.forEach(radio => {
                    radio.checked = false; // Uncheck any selected radio buttons
                    radio.disabled = true;
                });
            }
        }
    </script>
    
</x-guest-layout>
