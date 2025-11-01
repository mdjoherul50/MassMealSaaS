<x-landing-layout> <div class="hero-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block xl:inline">Manage Your Mess</span>
                <span class="block text-indigo-600 xl:inline">Like Never Before.</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Stop worrying about calculations. MassMeal SaaS handles all your daily meals, bazar costs, and deposits, giving you a perfect monthly report.
            </p>
            <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                <div class="rounded-md shadow">
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                        Get Started For Free
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Everything you need, all in one place
                </p>
            </div>
            <div class="mt-16">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md feature-icon text-white">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Member Management</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Easily add, edit, and view all members, including their login accounts and statements.
                        </dd>
                    </div>
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md feature-icon text-white">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Full Meal CRUD</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Use the daily bulk entry for speed, or the list view to edit/delete any single meal entry.
                        </dd>
                    </div>
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md feature-icon text-white">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Monthly Reports</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Automatically calculates the meal rate, total charges, and balance for every member.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Get up and running in minutes
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Managing your mess is as easy as 1-2-3.
                </p>
            </div>
            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 mx-auto rounded-full bg-indigo-100 text-indigo-600 font-bold text-2xl">1</div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Register Your Mess</h3>
                    <p class="mt-2 text-base text-gray-500">Create your account and give your mess a name. You are now the Mess Admin.</p>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 mx-auto rounded-full bg-indigo-100 text-indigo-600 font-bold text-2xl">2</div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Add Members & Data</h3>
                    <p class="mt-2 text-base text-gray-500">Add your members with their own login. Start entering daily meals, bazar costs, and deposits.</p>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 mx-auto rounded-full bg-indigo-100 text-indigo-600 font-bold text-2xl">3</div>
                    <h3 class="mt-6 text-xl font-medium text-gray-900">Get Monthly Reports</h3>
                    <p class="mt-2 text-base text-gray-500">At the end of the month, just click "Reports" to see the final calculation and member balances.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="pricing" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Simple, transparent pricing</h2>
                <p class="mt-4 text-lg text-gray-500">
                    Get started for free. No credit card required.
                </p>
            </div>
            <div class="mt-12 flex justify-center">
                <div class="w-full max-w-md border border-indigo-600 rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-medium text-gray-900">Free Plan</h3>
                    <p class="mt-4 text-gray-500">All essential features to get your mess organized.</p>
                    <div class="mt-8">
                        <span class="text-5xl font-extrabold text-gray-900">৳0</span>
                        <span class="text-xl font-medium text-gray-500">/mo</span>
                    </div>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-green-500 w-6 flex-shrink-0 mt-1"></i>
                            <span class="ml-3 text-base text-gray-500">Unlimited Members</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-green-500 w-6 flex-shrink-0 mt-1"></i>
                            <span class="ml-3 text-base text-gray-500">Unlimited Meal Entries</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-green-500 w-6 flex-shrink-0 mt-1"></i>
                            <span class="ml-3 text-base text-gray-500">Monthly Reports</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="mt-10 block w-full text-center px-6 py-3 border border-transparent rounded-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Register Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-bg">
        <div class="max-w-7xl mx-auto text-center py-16 px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Ready to simplify your mess?
            </h2>
            <p class="mt-4 text-lg leading-6 text-gray-500">
                Create an account today and take control of your mess finances.
            </p>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 sm:w-auto">
                Get Started For Free
            </a>
        </div>
    </div>

</x-landing-layout>
