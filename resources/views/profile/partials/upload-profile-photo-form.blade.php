<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Photo') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Upload your profile photo to personalize your account.') }}
        </p>
    </header>

    <div class="mt-6 flex items-center gap-6">
        @if($user->profile_photo)
            <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-indigo-100">
        @else
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif

        <form method="post" action="{{ route('profile.photo.upload') }}" enctype="multipart/form-data" class="flex-1">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Choose Photo') }}
                    </label>
                    <input 
                        type="file" 
                        id="profile_photo" 
                        name="profile_photo" 
                        accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                        required
                    >
                    <p class="mt-1 text-xs text-gray-500">{{ __('JPG, PNG, GIF up to 2MB') }}</p>
                    @error('profile_photo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fa-solid fa-upload mr-2"></i>
                        {{ __('Upload Photo') }}
                    </button>

                    @if (session('status') === 'photo-updated')
                        <p class="text-sm text-green-600 font-medium">
                            <i class="fa-solid fa-check-circle mr-1"></i>
                            {{ __('Photo uploaded successfully!') }}
                        </p>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div id="photo-preview" class="mt-4 hidden">
        <img id="preview-image" src="" alt="Preview" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-indigo-100">
    </div>

    <script>
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('photo-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</section>
