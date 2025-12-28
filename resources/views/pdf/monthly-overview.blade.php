<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('common.monthly_overview') }} - {{ $selectedMonth }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; }
        .container { width: 100%; margin: 0 auto; }
        .header { display: table; width: 100%; margin-bottom: 18px; }
        .header .left { display: table-cell; vertical-align: middle; width: 60%; }
        .header .right { display: table-cell; vertical-align: middle; width: 40%; text-align: right; }
        .title { font-size: 18px; font-weight: 700; margin: 0; }
        .sub { font-size: 12px; color: #374151; margin-top: 4px; }
        .meta { margin-top: 2px; color: #6b7280; }
        .card-row { display: table; width: 100%; margin-bottom: 12px; }
        .card { display: table-cell; border: 1px solid #e5e7eb; padding: 10px; vertical-align: top; }
        .card + .card { border-left: 0; }
        .k { font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; }
        .v { font-size: 14px; font-weight: 700; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; }
        th { background: #f9fafb; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; }
        td.num { text-align: right; }
        td.center { text-align: center; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .footer { margin-top: 14px; color: #6b7280; font-size: 10px; }
        .logo img { height: 34px; width: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="left">
                <div class="logo">
                    <x-site-logo />
                </div>
                <p class="title">{{ __('common.monthly_overview') }}</p>
                <div class="sub">
                    {{ Auth::user()->tenant?->name ?? config('app.name') }}
                </div>
                <div class="meta">
                    {{ __('common.select_month') }}: {{ $selectedMonth }}
                </div>
            </div>
            <div class="right">
                <div class="meta">{{ now()->format('Y-m-d H:i') }}</div>
                @if($memberId)
                    <div class="meta">{{ __('common.filter_by_member') }}: {{ $members->firstWhere('id', (int) $memberId)?->name }}</div>
                @endif
            </div>
        </div>

        <div class="card-row">
            <div class="card">
                <div class="k">{{ __('common.total_bazar') }}</div>
                <div class="v">{{ number_format($totalBazar, 2) }}</div>
            </div>
            <div class="card">
                <div class="k">{{ __('common.total_meal') }}</div>
                <div class="v">{{ number_format($totalMeal, 2) }}</div>
            </div>
            <div class="card">
                <div class="k">{{ __('common.meal_rate') }}</div>
                <div class="v">{{ number_format($avgMealRate, 4) }}</div>
            </div>
        </div>

        <div class="card-row">
            <div class="card">
                <div class="k">{{ __('common.total_deposits') }}</div>
                <div class="v">{{ number_format($totalDeposits, 2) }}</div>
            </div>
            <div class="card">
                <div class="k">{{ __('common.total_house_rent') }}</div>
                <div class="v">{{ number_format($totalHouseRent, 2) }}</div>
            </div>
            <div class="card">
                <div class="k">{{ __('common.net_balance') }}</div>
                <div class="v {{ $netBalance >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($netBalance, 2) }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">{{ __('common.sl') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th style="width: 90px;" class="center">{{ __('common.total_meal') }}</th>
                    <th style="width: 110px;" class="num">{{ __('house_rent.house_rent') }}</th>
                    <th style="width: 110px;" class="num">{{ __('common.deposit') }}</th>
                    <th style="width: 120px;" class="num">{{ __('common.total_charge') }}</th>
                    <th style="width: 110px;" class="num">{{ __('common.balance') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td class="center">{{ number_format($data['total_meal'], 2) }}</td>
                        <td class="num">{{ number_format($data['house_rent'], 2) }}</td>
                        <td class="num">{{ number_format($data['total_deposit'], 2) }}</td>
                        <td class="num">{{ number_format($data['total_charge'], 2) }}</td>
                        <td class="num {{ $data['balance'] >= 0 ? 'text-green' : 'text-red' }}">{{ number_format($data['balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #6b7280;">{{ __('common.no_data_for_month') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
