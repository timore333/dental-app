<?php

use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES (Guest Only)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

// ✅ LANGUAGE SWITCHER (Public Route)
Route::get('/set-locale/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        $locale = 'en';
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return redirect()->back();
})->name('set-locale');

// ============================================================
// AUTHENTICATED ROUTES (Protected by auth + verified)
// ============================================================
Route::middleware(['auth', 'verified', 'session.timeout', 'log.activity'])->group(function () {

    // ============================================================
    // MAIN DASHBOARD (Role-Based Redirect)
    // ============================================================
    Route::get('/dashboard', function () {
        $user = auth()->user();

        \Log::info('Dashboard access', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_email' => $user->email,
        ]);

        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'receptionist' => redirect()->route('receptionist.dashboard'),
            'accountant' => redirect()->route('accountant.dashboard'),
            default => view('dashboard'),
        };
    })->name('dashboard');

    // ============================================================
    // PHASE 5: DASHBOARDS (Role-Protected)
    // ============================================================

    // Admin Dashboard - ONLY ADMIN
    Route::get('/admin/dashboard', \App\Livewire\Dashboards\AdminDashboard::class)
        ->name('admin.dashboard')
        ->middleware('role:admin');

    // Doctor Dashboard - ONLY DOCTOR
    Route::get('/doctor/dashboard', \App\Livewire\Dashboards\DoctorDashboard::class)
        ->name('doctor.dashboard')
        ->middleware('role:doctor');

    // Receptionist Dashboard - ONLY RECEPTIONIST
    Route::get('/receptionist/dashboard', \App\Livewire\Dashboards\ReceptionistDashboard::class)
        ->name('receptionist.dashboard')
        ->middleware('role:receptionist');

    // Accountant Dashboard - ONLY ACCOUNTANT
    Route::get('/accountant/dashboard', \App\Livewire\Dashboards\AccountantDashboard::class)
        ->name('accountant.dashboard')
        ->middleware('role:accountant');

    // ============================================================
    // PHASE 5: REPORTS (All authenticated users)
    // ============================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/financial', \App\Livewire\Reports\FinancialReport::class)->name('financial');
        Route::get('/patient', \App\Livewire\Reports\PatientReport::class)->name('patient');
        Route::get('/insurance', \App\Livewire\Reports\InsuranceReport::class)->name('insurance');
        Route::get('/performance', \App\Livewire\Reports\PerformanceReport::class)->name('performance');
    });

    // ============================================================
    // PHASE 5: LEDGERS (Admin & Accountant Only)
    // ============================================================
    Route::middleware('role:admin,accountant')->prefix('ledgers')->name('ledgers.')->group(function () {
        Route::get('/patient/{patient_id}', \App\Livewire\Ledgers\PatientLedger::class)->name('patient');
        Route::get('/insurance/{company_id}', \App\Livewire\Ledgers\InsuranceCompanyLedger::class)->name('insurance');
        Route::get('/general', \App\Livewire\Ledgers\GeneralLedger::class)->name('general');
    });

    // ============================================================
    // CORE PROFILE & VERIFICATION ROUTES
    // ============================================================
    Route::get('/verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/profile', \App\Livewire\ProfileUpdate::class)->name('profile.edit');
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ============================================================
    // PATIENTS ROUTES
    // ============================================================
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', fn() => view('patients.index'))->name('index');
        Route::get('/create', fn() => view('patients.create'))->name('create');
        Route::get('/{patient}', fn(\App\Models\Patient $patient) => view('patients.show', ['patient' => $patient]))->name('show');
    });

    // ============================================================
    // APPOINTMENTS ROUTES
    // ============================================================
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', \App\Livewire\Appointments\AppointmentManagement::class)->name('index');
        Route::get('/create', \App\Livewire\Appointments\AppointmentManagement::class)->name('create');
        Route::get('/{id}', function ($id) {
            $appointment = \App\Models\Appointment::findOrFail($id);
            return view('livewire.appointments.show', ['appointment' => $appointment]);
        })->name('show');
    });

    // ============================================================
    // VISITS ROUTES
    // ============================================================
    Route::prefix('visits')->name('visits.')->group(function () {
        Route::get('/create/{appointmentId?}', \App\Livewire\Visits\VisitRecording::class)->name('create');
        Route::get('/list', \App\Livewire\Visits\VisitsList::class)->name('list');
        Route::get('/{id}', function ($id) {
            $visit = \App\Models\Visit::findOrFail($id);
            return view('livewire.visits.show', ['visit' => $visit]);
        })->name('show');
    });

    // ============================================================
    // INSURANCE ROUTES
    // ============================================================
    Route::prefix('insurance')->name('insurance.')->group(function () {
        Route::get('/', fn() => view('insurance.index'))->name('index');
        Route::get('/create', fn() => view('insurance.create'))->name('create');
        Route::get('/companies', fn() => view('insurance.companies'))->name('companies.index');
    });

    Route::get('/insurance-approvals', function () {
        $requests = \App\Models\InsuranceRequest::where('status', 'pending')->get();
        return view('insurance-approvals', ['requests' => $requests]);
    })->name('insurance-approvals')->middleware('role:receptionist,admin');

    Route::get('/insurance-requests/{request}', fn(\App\Models\InsuranceRequest $request) => view('insurance-requests.show', ['request' => $request]))->name('insurance-requests.show');

    // ============================================================
    // PROCEDURES ROUTES
    // ============================================================
    Route::prefix('procedures')->name('procedures.')->group(function () {
        Route::get('/', fn() => view('procedures.index'))->name('index');
    });

    // ============================================================
    // PAYMENTS & BILLING ROUTES
    // ============================================================
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/cash/{bill_id}', \App\Livewire\Bills\CashBillingFlow::class)->name('cash');
        Route::get('/insurance/{visit_id}', \App\Livewire\Insurance\InsuranceBillingFlow::class)->name('insurance');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', fn() => view('payments.index'))->name('index');
        Route::get('/create', fn() => view('payments.create'))->name('create');
    });

    // ============================================================
    // RECEIPTS ROUTES
    // ============================================================
    Route::prefix('receipts')->name('receipts.')->group(function () {
        Route::get('/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'show'])->name('show');
        Route::get('/{receipt}/print', [\App\Http\Controllers\ReceiptController::class, 'print'])->name('print');
        Route::post('/{receipt}/email', [\App\Http\Controllers\ReceiptController::class, 'email'])->name('email');
    });

    // ============================================================
    // GENERAL ROUTES
    // ============================================================
    Route::get('/reports', fn() => view('reports.index'))->name('reports.index');
    Route::get('/settings', fn() => view('settings.index'))->name('settings.index');

    // ============================================================
    // ADMIN ONLY ROUTES
    // ============================================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', \App\Livewire\UserManagement::class)->name('users.index');
        Route::get('/audit-logs', fn() => view('admin.audit-logs'))->name('audit-logs.index');
        Route::get('/settings', fn() => view('admin.settings'))->name('settings');
    });

    // ============================================================
    // DEBUG ROUTE (Remove in production)
    // ============================================================
    if (app()->isLocal()) {
        Route::get('/debug/user', function () {
            $user = auth()->user();
            $permissions = $user->role->permissions->pluck('name');
            return response()->json([
                'authenticated' => auth()->check(),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'user_role' => $user?->role,
                'is_admin' => $user?->isAdmin(),
                'is_doctor' => $user?->isDoctor(),
                'is_receptionist' => $user?->isReceptionist(),
                'is_accountant' => $user?->isAccountant(),
                'permissions'=>$permissions,
            ]);
        })->name('debug.user');
    }

}); // End of auth + verified middleware group

// ============================================================
// BREEZE AUTH ROUTES (From auth.php)
// ============================================================
require __DIR__.'/auth.php';
