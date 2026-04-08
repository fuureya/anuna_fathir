<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        @php($profileUser = isset($user) && $user ? $user : auth()->user())
        <div>
            <x-input-label for="fullname" :value="__('Full Name')" />
            <x-text-input id="fullname" name="fullname" type="text" class="mt-1 block w-full" value="{{ old('fullname', data_get($profileUser,'fullname')) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', data_get($profileUser,'email')) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="jenis_kelamin" :value="__('Gender')" />
                <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full border-gray-300 rounded">
                    <option value="">--</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin', data_get($profileUser,'jenis_kelamin'))==='Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin', data_get($profileUser,'jenis_kelamin'))==='Perempuan')>Perempuan</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
            </div>
            <div>
                <x-input-label for="usia" :value="__('Age')" />
                <x-text-input id="usia" name="usia" type="number" class="mt-1 block w-full" value="{{ old('usia', data_get($profileUser,'usia')) }}" />
                <x-input-error class="mt-2" :messages="$errors->get('usia')" />
            </div>
            <div>
                <x-input-label for="tempat_tanggal_lahir" :value="__('Birth Date')" />
                <x-text-input id="tempat_tanggal_lahir" name="tempat_tanggal_lahir" type="date" class="mt-1 block w-full" value="{{ old('tempat_tanggal_lahir', data_get($profileUser,'tempat_tanggal_lahir') ? \Illuminate\Support\Carbon::parse(data_get($profileUser,'tempat_tanggal_lahir'))->format('Y-m-d') : '') }}" />
                <x-input-error class="mt-2" :messages="$errors->get('tempat_tanggal_lahir')" />
            </div>
            <div>
                <x-input-label for="pendidikan_terakhir" :value="__('Last Education')" />
                <x-text-input id="pendidikan_terakhir" name="pendidikan_terakhir" type="text" class="mt-1 block w-full" value="{{ old('pendidikan_terakhir', data_get($profileUser,'pendidikan_terakhir')) }}" />
                <x-input-error class="mt-2" :messages="$errors->get('pendidikan_terakhir')" />
            </div>
        </div>

        <div>
            <x-input-label for="pekerjaan" :value="__('Occupation')" />
            <x-text-input id="pekerjaan" name="pekerjaan" type="text" class="mt-1 block w-full" value="{{ old('pekerjaan', data_get($profileUser,'pekerjaan')) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('pekerjaan')" />
        </div>

        <div>
            <x-input-label for="alamat_tinggal" :value="__('Address')" />
            <textarea id="alamat_tinggal" name="alamat_tinggal" class="mt-1 block w-full border-gray-300 rounded" rows="3">{{ old('alamat_tinggal', data_get($profileUser,'alamat_tinggal')) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat_tinggal')" />
        </div>

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            @if(data_get($profileUser,'profile_picture'))
                <div class="mb-2"><img src="{{ asset(data_get($profileUser,'profile_picture')) }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover"></div>
            @endif
            <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
