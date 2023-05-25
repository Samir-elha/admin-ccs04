<x-jobapplication-layout>
    <x-slot name="description">New description</x-slot>
    <x-slot name="title">Job Application Training</x-slot>


    @guest
        <p>Welcome to Job Application Training where you get equipped with the soft skills you need for your future job
            applications.
        </p>

    @endguest

    @auth
        {{-- delete this once the super user is deleted after development --}}
        @if (auth()->user()->type_id == 4)
            <div class="grid lg:grid-cols-4">
                <div class="w-full p-4 lg:w-80 group-hover:text-blue-200">

                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-questionaire') }}"
                            :active="request() - > routeIs('coordinator.manage-questionaire')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Questionaire</h2>
                        </a>
                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-student-accounts') }}"
                            :active="request() - > routeIs('coordinator.manage-student-accounts')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Student Accounts</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-company-users') }}"
                            :active="request() - > routeIs('coordinator.manage-company-users')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Company Users</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-companies') }}"
                            :active="request() - > routeIs('coordinator.manage-companies')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Companies</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-files') }}"
                            :active="request() - > routeIs('coordinator.manage-files')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Files</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-timeslots') }}"
                            :active="request() - > routeIs('coordinator.manage-timeslots')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Timeslots</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.view-feedback') }}"
                            :active="request() - > routeIs('coordinator.view-feedback')">
                            <h2 class="text-2xl font-bold text-gray-800">View Feedback</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-announcements') }}"
                            :active="request() - > routeIs('coordinator.manage-announcements')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Announcements</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.manage-edition') }}"
                            :active="request() - > routeIs('coordinator.manage-edition')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Edition</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.view-timeslot-schedule') }}"
                            :active="request() - > routeIs('coordinator.view-timeslot-schedule')">
                            <h2 class="text-2xl font-bold text-gray-800">View Timeslot Schedule</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.book-timeslot') }}"
                            :active="request() - > routeIs('coordinator.book-timeslot')">
                            <h2 class="text-2xl font-bold text-gray-800">Book Timeslot</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.view-announcements') }}"
                            :active="request() - > routeIs('coordinator.view-announcements')">
                            <h2 class="text-2xl font-bold text-gray-800">View Announcements</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.view-companies') }}"
                            :active="request() - > routeIs('coordinator.view-companies')">
                            <h2 class="text-2xl font-bold text-gray-800">View Companies</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('admin.fill-in-questionnaire') }}"
                            :active="request() - > routeIs('coordinator.manage-questionaire')">
                            <h2 class="text-2xl font-bold text-gray-800">Fill in Questionnaire</h2>
                        </a>


                    </div>
                </div>
            </div>
            {{-- coordinator only --}}


        @elseif(auth()->user()->type_id == 3)
            <div class="grid lg:grid-cols-4">
                <div class="w-full p-4 lg:w-80 group-hover:text-blue-200">

                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-questionaire') }}"
                            :active="request() - > routeIs('coordinator.manage-questionaire')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Questionaire</h2>
                        </a>
                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-student-accounts') }}"
                            :active="request() - > routeIs('coordinator.manage-questionaire')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Student Accounts</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-company-users') }}"
                           :active="request() - > routeIs('coordinator.manage-questionaire')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Company Users</h2>
                        </a>


                    </div>


                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-companies') }}"
                            :active="request() - > routeIs('coordinator.manage-companies')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Companies</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-files') }}"
                            :active="request() - > routeIs('coordinator.manage-files')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Files</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-timeslots') }}"
                            :active="request() - > routeIs('coordinator.manage-timeslots')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Timeslots</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.view-feedback') }}"
                            :active="request() - > routeIs('coordinator.view-feedback')">
                            <h2 class="text-2xl font-bold text-gray-800">View Feedback</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-announcements') }}"
                            :active="request() - > routeIs('coordinator.manage-announcements')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Announcements</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('coordinator.manage-edition') }}"
                            :active="request() - > routeIs('coordinator.manage-edition')">
                            <h2 class="text-2xl font-bold text-gray-800">Manage Edition</h2>
                        </a>


                    </div>
                </div>
            @elseif(auth()->user()->type_id == 2)
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('profile.show') }}">
                            <h2 class="text-2xl font-bold text-gray-800">Manage profile</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('company.view-timeslot-schedule') }}"
                            :active="request() - > routeIs('coordinator.view-timeslot-schedule')">
                            <h2 class="text-2xl font-bold text-gray-800">View Timeslot Schedule</h2>
                        </a>


                    </div>

                </div>
            @elseif(auth()->user()->type_id == 1)
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('student.book-timeslot') }}"
                            :active="request() - > routeIs('coordinator.book-timeslot')">
                            <h2 class="text-2xl font-bold text-gray-800">Book Timeslot</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('student.view-announcements') }}"
                            :active="request() - > routeIs('coordinator.view-announcements')">
                            <h2 class="text-2xl font-bold text-gray-800">View Announcements</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('student.view-companies') }}"
                            :active="request() - > routeIs('coordinator.view-companies')">
                            <h2 class="text-2xl font-bold text-gray-800">View Companies</h2>
                        </a>


                    </div>
                </div>
                <div class="w-full p-4 lg:w-80">
                    <div class="p-8 bg-white rounded shadow-md">
                        <a href="{{ route('student.fill-in-questionnaire') }}"
                            :active="request() - > routeIs('coordinator.fill-in-questionnaire')">
                            <h2 class="text-2xl font-bold text-gray-800">Fill in Questionnaire</h2>
                        </a>


                    </div>
                </div>
            </div>
        @endif

    @endauth

</x-jobapplication-layout>
