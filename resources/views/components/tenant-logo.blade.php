@php
    use App\Models\AppSetting;
    use Illuminate\Support\Facades\Auth;

    $tenantLogoPath = Auth::check() ? Auth::user()->tenant?->logo_path : null;
    $siteLogoPath = AppSetting::getValue('site_logo_path');
@endphp

@if($tenantLogoPath)
    <img src="{{ asset('storage/' . $tenantLogoPath) }}" {{ $attributes }} alt="{{ Auth::user()->tenant?->name ?? config('app.name') }}" />
@elseif($siteLogoPath)
    <img src="{{ asset('storage/' . $siteLogoPath) }}" {{ $attributes }} alt="{{ config('app.name') }}" />
@else
    <x-application-logo {{ $attributes }} />
@endif
