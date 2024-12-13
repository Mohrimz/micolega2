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
            <div class="mb-4">
                <label class="text-sm font-medium text-gray-700">{{ __('Select Skills') }}</label>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach ($skills as $skill)
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}>
                            {{ $skill->name }}
                        </label>
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
                                    <td class="px-2 py-1 border border-gray-300">
                                        <input 
                                            type="checkbox" 
                                            name="availabilities[]" 
                                            value="{{ $availabilityGroup->first()->id }}" 
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                            {{ old('availabilities') && in_array($availabilityGroup->first()->id, old('availabilities')) ? 'checked' : '' }}>
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
</x-guest-layout>
