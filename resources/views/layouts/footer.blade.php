<footer class="mt-auto border-t border-purple-200/50 bg-white/70 backdrop-blur-md">
    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
        <div class="text-center text-xs sm:text-sm text-gray-600">
            <p>
                &copy; {{ date('Y') }} {{ config('app.name', 'MassMeal') }}. {{ __('landing.all_rights_reserved') }}.
            </p>
            <p class="mt-1">
                {{ __('landing.developed_by') }}
                <a href="https://github.com/mdjoherul50" class="font-semibold text-purple-700 hover:text-purple-800 hover:underline" target="_blank">Jahirul Islam</a>
            </p>
        </div>
    </div>
</footer>