<nav class="container mx-auto p-4 flex justify-between">
    {{-- left navigation --}}
    <div class="flex items-center space-x-2">
        {{-- Logo --}}
        <a href="{{ route('home') }}">
            Job Training Application
        </a>
    </div>

    {{-- right navigation --}}
    <div class="relative flex items-center space-x-2">
        <div class="w-48 text-center">
            <x-dropdown width="48">
                <x-slot name="trigger">
                    <p>{{ __('layout.' . app()->getLocale()) }}</p>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link href="{{ route('locale', ['locale' => 'en']) }}">{{ __('layout.en') }}
                    </x-dropdown-link>
                    <x-dropdown-link href="{{ route('locale', ['locale' => 'nl']) }}">{{ __('layout.nl') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
        @guest
            <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                Login
            </x-nav-link>
        @endguest

        {{--            <form method="POST" action="{{ route('logout') }}"> --}}
        {{--                @csrf --}}
        {{--                <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">Logout</button> --}}
        {{--            </form> --}}

        {{-- dropdown navigation --}}
        @auth
            <x-dropdown align="right" width="48">
                {{-- avatar --}}
                <x-slot name="trigger">
                    <img class="rounded-full h-8 w-8 object-cover cursor-pointer"
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->firstName) }}"
                        alt="{{ auth()->user()->firstName }}">
                </x-slot>
                <x-slot name="content">
                    {{-- all users --}}

                    <x-dropdown-link href="{{ route('profile.show') }}">Manage Profile</x-dropdown-link>
                    {{-- delete this super user after development --}}
                    @if (auth()->user()->type_id == 4)
                        @if (request()->routeIs('home') == false)
                            <x-dropdown-link href="{{ route('admin.manage-questionaire') }}" :active="request()->routeIs('admin.manage-questionaire')">Manage
                                Questionaire</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-company-users') }}" :active="request()->routeIs('admin.manage-company-users')">Manage
                                Company Users</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-student-accounts') }}" :active="request()->routeIs('admin.manage-student-accounts')">Manage
                                Student Accounts</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-companies') }}" :active="request()->routeIs('admin.manage-companies')">Manage
                                Companies</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-files') }}" :active="request()->routeIs('admin.manage-files')">Manage Files
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-timeslots') }}" :active="request()->routeIs('admin.manage-timeslots')">Manage
                                Timeslots</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.view-feedback') }}" :active="request()->routeIs('admin.view-feedback')">View Feedback
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-announcements') }}" :active="request()->routeIs('admin.manage-announcements')">Manage
                                Anouncements</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-edition') }}" :active="request()->routeIs('admin.manage-edition')">Manage Edition
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.view-timeslot-schedule') }}" :active="request()->routeIs('admin.view-timeslot-schedule')">View
                                Timeslot Schedule</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.book-timeslot') }}" :active="request()->routeIs('admin.book-timeslot')">Book Timeslot
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.view-announcements') }}" :active="request()->routeIs('admin.view-announcements')">View
                                Announcements</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.view-companies') }}" :active="request()->routeIs('admin.view-companies')">View Companies
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.fill-in-questionnaire') }}" :active="request()->routeIs('admin.fill-in-questionnaire')">Fill in
                                Questionnaire</x-dropdown-link>
                        @endif
                        {{-- coordinator only --}}
                    @elseif(auth()->user()->type_id == 3)
                        @if (request()->routeIs('home') == false)
                            <x-dropdown-link href="{{ route('coordinator.manage-questionaire') }}" :active="request()->routeIs('coordinator.manage-questionaire')">
                                Manage Questionaire</x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin.manage-student-accounts') }}" :active="request()->routeIs('admin.manage-student-accountes')">Manage
                                Student Accounts</x-dropdown-link>
                            <x-dropdown-link href="{{ route('coordinator.manage-companies') }}" :active="request()->routeIs('coordinator.manage-companies')">Manage
                                Companies</x-dropdown-link>
                            <x-dropdown-link href="{{ route('coordinator.manage-files') }}" :active="request()->routeIs('coordinator.manage-files')">Manage Files
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('coordinator.manage-timeslots') }}" :active="request()->routeIs('coordinator.manage-timeslots')">Manage
                                Timeslots</x-dropdown-link>
                            <x-dropdown-link href="{{ route('coordinator.view-feedback') }}" :active="request()->routeIs('coordinator.view-feedback')">View
                                Feedback</x-dropdown-link>
                            <x-dropdown-link href="{{ route('coordinator.manage-announcements') }}" :active="request()->routeIs('coordinator.manage-announcements')">
                                Manage Anouncements</x-dropdown-link>
                            <x-dropdown-link href="{{ route('coordinator.manage-edition') }}" :active="request()->routeIs('coordinator.manage-edition')">Manage
                                Edition</x-dropdown-link>
                        @endif
                    @elseif(auth()->user()->type_id == 2)
                        <x-dropdown-link href="{{ route('profile.show') }}">Manage Profile</x-dropdown-link>
                        @if (request()->routeIs('home') == false)
                            <x-dropdown-link href="{{ route('company.view-timeslot-schedule') }}" :active="request()->routeIs('company.view-timeslot-schedule')">View
                                Timeslot Schedule</x-dropdown-link>
                        @endif
                    @elseif(auth()->user()->type_id == 1)
                        @if (request()->routeIs('home') == false)
                            <x-dropdown-link href="{{ route('student.book-timeslot') }}" :active="request()->routeIs('student.book-timeslot')">Book Timeslot
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('student.view-announcements') }}" :active="request()->routeIs('student.view-announcements')">View
                                Announcements</x-dropdown-link>
                            <x-dropdown-link href="{{ route('student.view-companies') }}" :active="request()->routeIs('student.view-companies')">View Companies
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('student.fill-in-questionnaire') }}" :active="request()->routeIs('student.fill-in-questionnaire')">Fill in
                                Questionnaire</x-dropdown-link>
                        @endif
                    @endif
                    <div class="border-t border-gray-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">Logout</button>
                    </form>

                </x-slot>
            </x-dropdown>
        @endauth
    </div>
</nav>
