<nav x-data="{ open: false }" class="bg-purple-600 border-b border-purple-500">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logoMiColega.jpeg') }}" alt="Logo" class="block h-9 w-auto" />
                    </a>
                </div>

               <!-- Navigation Links -->
               <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <!-- Dashboard Link (Visible to All) -->
    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white font-bold">
        {{ __('Dashboard') }}
    </x-nav-link> 
    <!-- 'lol' Link (Visible to Admin Only) -->
                    @if(auth()->user()->hasRole('admin'))
                        <x-nav-link href="{{ route('lol') }}" :active="request()->routeIs('lol')" class="text-white font-bold">
                            {{ __('Requested Skills') }}
                        </x-nav-link>
                    @endif

    <!-- Other Links (Visible to Non-Admin Users Only) -->
    @if(!auth()->user()->hasRole('admin'))
        <!-- Teach Link -->
        <x-nav-link href="{{ route('teach') }}" :active="request()->routeIs('teach')" class="text-white font-bold">
            {{ __('Teach') }}
        </x-nav-link>
        
        <!-- Sessions Link -->
        <x-nav-link href="{{ route('sessions.index') }}" :active="request()->routeIs('sessions.*')" class="text-white font-bold">
            {{ __('Sessions') }}
        </x-nav-link>
        
        <!-- Group Sessions Link -->
        <x-nav-link href="{{ route('group-sessions') }}" :active="request()->routeIs('group-sessions')" class="text-white font-bold">
            {{ __('Webinars') }}
        </x-nav-link>
    @endif
</div>
                <div class="ms-3 relative text-black-800">
                    <x-dropdown align="right" width="4">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::check() ? Auth::user()->profile_photo_url : '' }}" alt="{{ Auth::check() ? Auth::user()->name : '' }}" />
                                </button>
                            @else
                                @if (Auth::check())
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-[#7011B8] hover:bg-[#590f9b] focus:outline-none transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-[#7011B8] hover:bg-[#590f9b] focus:outline-none transition ease-in-out duration-150">
                                            {{ __('Guest') }}
                                        </button>
                                    </span>
                                @endif
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}" class="text-gray-900 font-bold">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}" class="text-gray-900 font-bold">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" class="text-gray-900 font-bold"
                                                 @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-[#590f9b] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white font-bold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::check() ? Auth::user()->profile_photo_url : '' }}" alt="{{ Auth::check() ? Auth::user()->name : '' }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-white">{{ Auth::check() ? Auth::user()->name : '' }}</div>
                    <div class="font-medium text-sm text-gray-300">{{ Auth::check() ? Auth::user()->email : '' }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-gray-900 font-bold">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" class="text-gray-900 font-bold">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" class="text-gray-900 font-bold"
                                           @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
