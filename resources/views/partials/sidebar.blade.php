<aside class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white shadow-lg overflow-y-auto">

    <!-- Logo -->
    <div class="p-6 border-b border-slate-700">
        <h1 class="text-2xl font-bold text-teal-400">Dental Clinic</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition @if(request()->routeIs('dashboard')) bg-slate-700 text-white @endif">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 7a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm8-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6a1 1 0 011-1z"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Patients Section -->
        <div class="mt-4">
            <button class="w-full flex items-center px-6 py-2 text-gray-300 hover:bg-slate-700 hover:text-white transition"
                    onclick="toggleMenu('patients-menu')">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                </svg>
                <span>Patients</span>
                <svg class="w-4 h-4 ml-auto" id="patients-arrow" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>

            <!-- Patients Submenu -->
            <div id="patients-menu" class="hidden bg-slate-700">
                <a href="{{ route('patients.index') }}"
                   class="block px-12 py-2 text-gray-300 hover:text-white hover:bg-slate-600 transition @if(request()->routeIs('patients.index')) bg-slate-600 text-white @endif">
                    All Patients
                </a>
                <a href="{{ route('patients.create') }}"
                   class="block px-12 py-2 text-gray-300 hover:text-white hover:bg-slate-600 transition @if(request()->routeIs('patients.create')) bg-slate-600 text-white @endif">
                    New Patient
                </a>
            </div>
        </div>

        <!-- Appointments Section -->
        <div class="mt-2">
            <button class="w-full flex items-center px-6 py-2 text-gray-300 hover:bg-slate-700 hover:text-white transition"
                    onclick="toggleMenu('appointments-menu')">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5H4v8a2 2 0 002 2h12a2 2 0 002-2V7h-2v1a1 1 0 11-2 0V7H9v1a1 1 0 11-2 0V7H6v1a1 1 0 11-2 0V7z"></path>
                </svg>
                <span>Appointments</span>
                <svg class="w-4 h-4 ml-auto" id="appointments-arrow" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>

            <!-- Appointments Submenu -->
            <div id="appointments-menu" class="hidden bg-slate-700">
                <a href="{{ route('appointments.index') }}"
                   class="block px-12 py-2 text-gray-300 hover:text-white hover:bg-slate-600 transition @if(request()->routeIs('appointments.index')) bg-slate-600 text-white @endif">
                    All Appointments
                </a>
                <a href="{{ route('appointments.create') }}"
                   class="block px-12 py-2 text-gray-300 hover:text-white hover:bg-slate-600 transition @if(request()->routeIs('appointments.create')) bg-slate-600 text-white @endif">
                    New Appointment
                </a>
            </div>
        </div>

        <!-- Insurance Section -->
        <div class="mt-2">
            <button class="w-full flex items-center px-6 py-2 text-gray-300 hover:bg-slate-700 hover:text-white transition"
                    onclick="toggleMenu('insurance-menu')">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a1 1 0 001 1h14a1 1 0 001-1V6a2 2 0 00-2-2H4zm0 12a2 2 0 00-2 2v.5a.5.5 0 00.5.5h15a.5.5 0 00.5-.5V18a2 2 0 00-2-2H4z"></path>
                </svg>
                <span>Insurance</span>
                <svg class="w-4 h-4 ml-auto" id="insurance-arrow" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>

            <!-- Insurance Submenu -->
            <div id="insurance-menu" class="hidden bg-slate-700">
                <a href="{{ route('insurance.index') }}"
                   class="block px-12 py-2 text-gray-300 hover:text-white hover:bg-slate-600 transition @if(request()->routeIs('insurance.index')) bg-slate-600 text-white @endif">
                    Insurance Requests
                </a>
                <a href="{{ route('insurance.companies.index') }}"
                   class="block px-12 py-2 text-gray-300 hover:text-white hover:bg-slate-600 transition @if(request()->routeIs('insurance.companies.index')) bg-slate-600 text-white @endif">
                    Companies
                </a>
            </div>
        </div>

        <!-- Procedures -->
        <a href="{{ route('procedures.index') }}"
           class="flex items-center px-6 py-3 mt-2 text-gray-300 hover:bg-slate-700 hover:text-white transition @if(request()->routeIs('procedures.index')) bg-slate-700 text-white @endif">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"></path>
            </svg>
            <span>Procedures</span>
        </a>

        <!-- Payments -->
        <a href="{{ route('payments.index') }}"
           class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition @if(request()->routeIs('payments.index')) bg-slate-700 text-white @endif">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v4a1 1 0 001 1h14a1 1 0 001-1V6a2 2 0 00-2-2H4zm0 12a2 2 0 00-2 2v.5a.5.5 0 00.5.5h15a.5.5 0 00.5-.5V18a2 2 0 00-2-2H4z"></path>
            </svg>
            <span>Payments</span>
        </a>

        <!-- Reports -->
        <a href="{{ route('reports.index') }}"
           class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition @if(request()->routeIs('reports.index')) bg-slate-700 text-white @endif">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
            </svg>
            <span>Reports</span>
        </a>

        <!-- Settings -->
        <a href="{{ route('settings.index') }}"
           class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition @if(request()->routeIs('settings.index')) bg-slate-700 text-white @endif">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
            </svg>
            <span>Settings</span>
        </a>

    </nav>

    <!-- User Section -->
    <div class="absolute bottom-0 w-full border-t border-slate-700 bg-slate-800 p-4">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-teal-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">{{ auth()->user()->role?->name ?? 'User' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Logout
            </button>
        </form>
    </div>

</aside>

<script>
    function toggleMenu(menuId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(menuId.replace('-menu', '-arrow'));

        menu.classList.toggle('hidden');

        if (arrow) {
            arrow.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    }

    // Auto-open menus if current route is in that section
    document.addEventListener('DOMContentLoaded', function() {
        const currentRoute = '{{ request()->route()->getName() }}';

        if (currentRoute.startsWith('patients.')) {
            document.getElementById('patients-menu').classList.remove('hidden');
            document.getElementById('patients-arrow').style.transform = 'rotate(180deg)';
        }
        if (currentRoute.startsWith('appointments.')) {
            document.getElementById('appointments-menu').classList.remove('hidden');
            document.getElementById('appointments-arrow').style.transform = 'rotate(180deg)';
        }
        if (currentRoute.startsWith('insurance.')) {
            document.getElementById('insurance-menu').classList.remove('hidden');
            document.getElementById('insurance-arrow').style.transform = 'rotate(180deg)';
        }
    });
</script>
