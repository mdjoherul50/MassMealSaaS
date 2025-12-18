<div class="relative inline-block text-left">
    <form action="{{ route('language.switch') }}" method="POST" id="language-form">
        @csrf
        <select name="locale" onchange="document.getElementById('language-form').submit()" 
                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                {{ __('common.english') }}
            </option>
            <option value="bn" {{ app()->getLocale() == 'bn' ? 'selected' : '' }}>
                {{ __('common.bangla') }}
            </option>
        </select>
    </form>
</div>
