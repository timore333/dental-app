<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ============================================================
// PUBLIC ROUTES (Accessible without authentication)
// ============================================================

Route::get('/test', function () {
    return \App\Models\Visit::get();
});


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

// ============================================================
// LANGUAGE SWITCHER (Public Route - Accessible to all)
// ============================================================

Route::get('/set-locale/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        $locale = 'en';
    }

    // Update user preference if authenticated
    if (auth()->check()) {
        auth()->user()->update(['locale' => $locale]);
    }

    // Update session
    session(['locale' => $locale]);

    // Set app locale
    app()->setLocale($locale);

    return redirect()->back();
})->name('set-locale');

// ============================================================
// AUTHENTICATED ROUTES (Protected by auth + verified)
// ============================================================

Route::middleware(['auth', 'verified', 'session.timeout', 'log.activity'])->group(function () {

    // ============================================================
    // PHASE 5: MAIN DASHBOARD (Role-Based Redirect)
    // ============================================================

    Route::get('/dashboard', function () {
        $user = auth()->user();
        // ✅ Ensure role is loaded
        if (!$user->role) {
            $user->load('role');
        }

        $roleName = $user->role?->name;
        \Log::info('Dashboard access', [
            'user_id' => $user->id,
            'user_role' => $roleName,
            'user_email' => $user->email,
        ]);

        return match ($roleName) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Doctor' => redirect()->route('doctor.dashboard'),
            'Receptionist' => redirect()->route('receptionist.dashboard'),
            'Accountant' => redirect()->route('accountant.dashboard'),
            default => view('dashboard'),
        };
    })->name('dashboard');

    // ============================================================
    // PHASE 5: DASHBOARDS (Role-Protected)
    // ============================================================

    // Admin Dashboard - ONLY Admin ROLE
    Route::get('/admin/dashboard', \App\Livewire\Dashboards\AdminDashboard::class)
        ->name('admin.dashboard')
        ->middleware('role:Admin');

    // Doctor Dashboard - ONLY Doctor ROLE
    Route::get('/doctor/dashboard', \App\Livewire\Dashboards\DoctorDashboard::class)
        ->name('doctor.dashboard')
        ->middleware('role:Doctor');

    // Receptionist Dashboard - ONLY Receptionist ROLE
    Route::get('/receptionist/dashboard', \App\Livewire\Dashboards\ReceptionistDashboard::class)
        ->name('receptionist.dashboard')
        ->middleware('role:Receptionist');

    // Accountant Dashboard - ONLY Accountant ROLE
    Route::get('/accountant/dashboard', \App\Livewire\Dashboards\AccountantDashboard::class)
        ->name('accountant.dashboard')
        ->middleware('role:Accountant');

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

    Route::middleware('role:Admin,Accountant')->prefix('ledgers')->name('ledgers.')->group(function () {
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
    // PHASE 2-3: PATIENTS ROUTES
    // ============================================================

    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', \App\Livewire\Patients\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Patients\Create::class)->name('create');
        Route::get('/{patient}/edit', \App\Livewire\Patients\Edit::class)->name('edit');
           Route::get('/import', \App\Livewire\Patients\PatientImport::class)->name('import');
    });
    // ============================================================
    // PHASE 2-3: APPOINTMENTS ROUTES
    // ============================================================


    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', fn() => view('appointments.appointments'))->name('index');

        // Appointment Calendar
        Route::get('/calendar', \App\Livewire\Appointments\Calendar::class)->name('calendar');

    });

    // ============================================================
    // PHASE 2-3: VISITS ROUTES
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
    // PHASE 3: INSURANCE ROUTES
    // ============================================================

    Route::prefix('insurance')->name('insurance.')->group(function () {
        Route::get('/', fn() => view('insurance.index'))->name('index');
        Route::get('/create', fn() => view('insurance.create'))->name('create');
        Route::get('/companies', fn() => view('insurance.companies'))->name('companies.index');
    });

    Route::get('/insurance-approvals', function () {
        $requests = \App\Models\InsuranceRequest::where('status', 'pending')->get();
        return view('insurance-approvals', ['requests' => $requests]);
    })->name('insurance-approvals')->middleware('role:Receptionist,Admin');

    Route::get('/insurance-requests/{request}',
        fn(\App\Models\InsuranceRequest $request) => view('insurance-requests.show', ['request' => $request])
    )->name('insurance-requests.show');

    // ============================================================
    // PHASE 3: PROCEDURES ROUTES
    // ============================================================

    Route::prefix('procedures')->name('procedures.')->group(function () {
        Route::get('/', App\Livewire\Procedures\Index::class)->name('index');
        Route::get('/create', App\Livewire\Procedures\Create::class)->name('create');
    });

    // ============================================================
    // PHASE 4: PAYMENTS & BILLING ROUTES
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
    // PHASE 4: RECEIPTS ROUTES
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
    // PHASE 5: ADMIN ONLY ROUTES (✅ FULLY FIXED)
    // ============================================================

    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', \App\Livewire\UserManagement::class)->name('users.index');
        Route::get('/audit-logs', fn() => view('admin.audit-logs'))->name('audit-logs.index');
        Route::get('/settings', fn() => view('admin.settings'))->name('settings');
    });


    // ============================================================
    // PHASE 6: NOTIFICATIONS (All authenticated users)
    // ============================================================

    Route::prefix('notifications')->name('notifications.')->group(function () {

        // Notification Center - Full page view
        Route::get('/', \App\Livewire\Notifications\NotificationCenter::class)
            ->name('index');

        // Mark notification as read (AJAX)
        Route::post('/mark-read/{notification_id}', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
            ->name('mark-read');

        // Mark all notifications as read
        Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
            ->name('mark-all-read');

        // Delete a notification
        Route::delete('/{notification_id}', [\App\Http\Controllers\NotificationController::class, 'delete'])
            ->name('delete');

    });

    // ============================================================
    // PHASE 6: NOTIFICATION PREFERENCES (Settings)
    // ============================================================

    Route::prefix('settings/notifications')->name('settings.notifications.')->group(function () {

        // Notification preferences page
        Route::get('/', \App\Livewire\Settings\NotificationPreferences::class)
            ->name('index');

        // Update preferences (AJAX)
        Route::post('/update', [\App\Http\Controllers\NotificationController::class, 'updatePreferences'])
            ->name('update');

    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/user/theme', function (Request $request) {
            Auth::user()->update(['theme' => $request->input('theme')]);
            return response()->json(['success' => true]);
        });

        Route::post('/user/language', function ($request) {
            Auth::user()->update(['locale' => $request->input('language')]);
            session(['locale' => $request->input('language')]);
            return response()->json(['success' => true]);
        });
    });


    // ============================================================
    // DEBUG ROUTE (Remove in production)
    // ============================================================

    if (app()->isLocal()) {
        Route::get('/debug/user', function () {
            $user = auth()->user();
            // ✅ Load role if not loaded
            if (!$user->role) {
                $user->load('role');
            }

            // Get permissions if role exists
            $permissions = $user->role?->permissions?->pluck('name') ?? collect();

            return response()->json([
                'authenticated' => auth()->check(),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'role_id' => $user?->role_id,
                'role_name' => $user?->role?->name,
                'is_admin' => $user?->isAdmin(),
                'is_doctor' => $user?->isDoctor(),
                'is_receptionist' => $user?->isReceptionist(),
                'is_accountant' => $user?->isAccountant(),
                'permissions' => $permissions->toArray(),
            ]);
        })->name('debug.user');
    }

}); // End of auth + verified middleware group

// ============================================================
// BREEZE AUTH ROUTES
// ============================================================

require __DIR__ . '/auth.php';
