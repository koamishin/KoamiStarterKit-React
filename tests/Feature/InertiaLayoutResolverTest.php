<?php

declare(strict_types=1);

use App\Settings\ApplicationFeaturesSettings;
use Illuminate\Support\Facades\File;
use Inertia\Testing\AssertableInertia;
use Inertia\Testing\AssertableInertia as Assert;

test('react app entry wires the shared layout hierarchy', function (): void {
    $source = File::get(base_path('resources/js/app.tsx'));

    expect($source)
        ->toContain("case name === 'welcome':")
        ->toContain("case name.startsWith('auth/'):")
        ->toContain("case name.startsWith('settings/'):")
        ->toContain('return null;')
        ->toContain('return AppLayout;')
        ->toContain('initializeColorTheme');
});

test('react pages use kebab-case filenames matching backend components', function (): void {
    foreach (['resources/js/pages/auth/login.tsx', 'resources/js/pages/dashboard.tsx', 'resources/js/pages/welcome.tsx'] as $path) {
        expect(File::exists(base_path($path)))->toBeTrue($path.' should exist');
    }
});

test('react auth layout follows the application auth_layout setting', function (): void {
    $source = File::get(base_path('resources/js/layouts/auth-layout.tsx'));

    expect($source)
        ->toContain('authLayout')
        ->toContain('auth-card-layout')
        ->toContain('auth-split-layout')
        ->toContain('auth-simple-layout');
});

test('auth layout setting is shared with inertia pages', function (): void {
    $applicationFeaturesSettings = app(ApplicationFeaturesSettings::class);
    $applicationFeaturesSettings->auth_layout = 'card';
    $applicationFeaturesSettings->save();

    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $assert): AssertableInertia => $assert
        ->where('authLayout', 'card')
    );
});
