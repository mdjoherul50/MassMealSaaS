@php
    use App\Models\AppSetting;

    $siteLogoPath = AppSetting::getValue('site_logo_path');
@endphp

@if($siteLogoPath)
    <img src="{{ asset('storage/' . $siteLogoPath) }}" {{ $attributes }} alt="{{ config('app.name') }}" />
@else
    <x-application-logo {{ $attributes }} />
@endif
