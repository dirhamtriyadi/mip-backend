<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Dashboard > Master Data > Work Schedules
Breadcrumbs::for('work-schedules', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Master Data');
    $trail->push('Work Schedules', route('work-schedules.index'));
});

// Dashboard > Master Data > Work Schedules > [Work Schedule] > Edit
Breadcrumbs::for('work-schedules.edit', function (BreadcrumbTrail $trail, $workSchedule) {
    $trail->parent('work-schedules');
    $trail->push('Edit', route('work-schedules.edit', $workSchedule));
});

// Dashboard > Master Data > Work Schedules > Create
Breadcrumbs::for('work-schedules.create', function (BreadcrumbTrail $trail) {
    $trail->parent('work-schedules');
    $trail->push('Create', route('work-schedules.create'));
});

// Dashboard > Master Data > Annual Holidays
Breadcrumbs::for('annual-holidays', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Master Data');
    $trail->push('Annual Holidays', route('annual-holidays.index'));
});

// Dashboard > Master Data > Annual Holidays > [Annual Holiday] > Edit
Breadcrumbs::for('annual-holidays.edit', function (BreadcrumbTrail $trail, $annualHoliday) {
    $trail->parent('annual-holidays');
    $trail->push('Edit', route('annual-holidays.edit', $annualHoliday));
});

// Dashboard > Master Data > Annual Holidays > Create
Breadcrumbs::for('annual-holidays.create', function (BreadcrumbTrail $trail) {
    $trail->parent('annual-holidays');
    $trail->push('Create', route('annual-holidays.create'));
});

// Dashboard > Master Data > Banks
Breadcrumbs::for('banks', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Master Data');
    $trail->push('Banks', route('banks.index'));
});

// Dashboard > Master Data > Banks > [Bank] > Edit
Breadcrumbs::for('banks.edit', function (BreadcrumbTrail $trail, $bankAccount) {
    $trail->parent('banks');
    $trail->push('Edit', route('banks.edit', $bankAccount));
});

// Dashboard > Master Data > Banks > Create
Breadcrumbs::for('banks.create', function (BreadcrumbTrail $trail) {
    $trail->parent('banks');
    $trail->push('Create', route('banks.create'));
});

// Dashboard > Master Data > Customers
Breadcrumbs::for('customers', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Master Data');
    $trail->push('Customers', route('customers.index'));
});

// Dashboard > Master Data > Customers > [Customer] > Edit
Breadcrumbs::for('customers.edit', function (BreadcrumbTrail $trail, $bankAccount) {
    $trail->parent('customers');
    $trail->push('Edit', route('customers.edit', $bankAccount));
});

// Dashboard > Master Data > Customers > Create
Breadcrumbs::for('customers.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customers');
    $trail->push('Create', route('customers.create'));
});

// Dashboard > Absen > Attendances
Breadcrumbs::for('attendances', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Absen');
    $trail->push('Attendances', route('attendances.index'));
});

// Dashboard > Absen > Attendances > [Attendance] > Edit
Breadcrumbs::for('attendances.edit', function (BreadcrumbTrail $trail, $attendance) {
    $trail->parent('attendances');
    $trail->push('Edit', route('attendances.edit', $attendance));
});

// Dashboard > Absen > Attendances > Create
Breadcrumbs::for('attendances.create', function (BreadcrumbTrail $trail) {
    $trail->parent('attendances');
    $trail->push('Create', route('attendances.create'));
});

// Dashboard > Absen > Leaves
Breadcrumbs::for('leaves', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Absen');
    $trail->push('Leaves', route('leaves.index'));
});

// Dashboard > Absen > Leaves > [Leave] > Edit
Breadcrumbs::for('leaves.edit', function (BreadcrumbTrail $trail, $leaves) {
    $trail->parent('leaves');
    $trail->push('Edit', route('leaves.edit', $leaves));
});

// Dashboard > Absen > Leaves > Create
Breadcrumbs::for('leaves.create', function (BreadcrumbTrail $trail) {
    $trail->parent('leaves');
    $trail->push('Create', route('leaves.create'));
});

// Dashboard > Customer Billings
Breadcrumbs::for('customer-billings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Customer Billings', route('customer-billings.index'));
});

// Dashboard > Customer Billings > [Billing] > Edit
Breadcrumbs::for('customer-billings.edit', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('customer-billings');
    $trail->push('Edit', route('customer-billings.edit', $role));
});

// Dashboard > Customer Billings > Create
Breadcrumbs::for('customer-billings.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customer-billings');
    $trail->push('Create', route('customer-billings.create'));
});

// Dashboard > Prospective Customers
Breadcrumbs::for('prospective-customers', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Prospective Customers', route('prospective-customers.index'));
});

// Dashboard > Prospective Customers > [Prospective Customer]
// Breadcrumbs::for('prospective-customers.show', function (BreadcrumbTrail $trail, $prospectiveCustomer) {
//     $trail->parent('prospective-customers');
//     $trail->push($prospectiveCustomer->name, route('prospective-customers.show', $prospectiveCustomer));
// });

// Dashboard > Prospective Customers > [Prospective Customer] > Edit
Breadcrumbs::for('prospective-customers.edit', function (BreadcrumbTrail $trail, $prospectiveCustomer) {
    $trail->parent('prospective-customers', $prospectiveCustomer);
    $trail->push('Edit', route('prospective-customers.edit', $prospectiveCustomer));
    $trail->push($prospectiveCustomer->name, route('prospective-customers.show', $prospectiveCustomer));
});

// Dashboard > Prospective Customers > Create
Breadcrumbs::for('prospective-customers.create', function (BreadcrumbTrail $trail) {
    $trail->parent('prospective-customers');
    $trail->push('Create', route('prospective-customers.create'));
});

// Dashboard > Laporan > Officer Reports
Breadcrumbs::for('officer-reports', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Laporan');
    $trail->push('Officer Reports', route('officer-reports.index'));
});

// Dashboard > Laporan > Officer Reports > [Officer Report] > Show
Breadcrumbs::for('officer-reports.show', function (BreadcrumbTrail $trail, $officerReport) {
    $trail->parent('dashboard');
    $trail->push('Laporan');
    $trail->push('Officer Reports', route('officer-reports.index'));
    $trail->push('Show');
    $trail->push($officerReport->name, route('officer-reports.show', $officerReport->id));
});

// Dashboard > Laporan > Attendance Reports
Breadcrumbs::for('attendance-reports', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Laporan');
    $trail->push('Attendance Reports', route('attendance-reports.index'));
});

// Dashboard > Laporan > Attendance Reports > [Attendance Report] > Show
Breadcrumbs::for('attendance-reports.show', function (BreadcrumbTrail $trail, $officerReport) {
    $trail->parent('dashboard');
    $trail->push('Laporan');
    $trail->push('Attendance Reports', route('attendance-reports.index'));
    $trail->push('Show');
    $trail->push($officerReport->name, route('attendance-reports.show', $officerReport->id));
});

// Dashboard > Laporan > Customer Billing Reports
Breadcrumbs::for('customer-billing-reports', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Laporan');
    $trail->push('Customer Billing Reports', route('customer-billing-reports.index'));
});

// Dashboard > Roles
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Roles', route('roles.index'));
});

// Dashboard > Roles > [Role] > Edit
Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('roles');
    $trail->push('Edit', route('roles.edit', $role));
});

// Dashboard > Roles > Create
Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push('Create', route('roles.create'));
});

// Dashboard > Users
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('users.index'));
});

// Dashboard > Users > [User]
// Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, $user) {
//     $trail->parent('users');
//     $trail->push($user->name, route('users.show', $user));
// });

// Dashboard > Users > [User] > Edit
Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users', $user);
    $trail->push('Edit', route('users.edit', $user));
});

// Dashboard > Users > Create
Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push('Create', route('users.create'));
});
