<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudentClass;
use Livewire\WithFileUploads;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $role = '';
    
    public $lecturer;
    public $nidn;
    public $nip;
    public $lecturerSignature;
    public $lecturerQrSignature;

    public $student;
    public $studentClassId;
    public $studentClass;
    public $nim;

    public $phoneNumber;
    public $address;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->role = Auth::user()->roles->first()->name;

        if ($this->role == 'dosenWali' || $this->role == 'kaprodi' || $this->role == 'kajur') {
            
            $this->lecturer = Auth::user()->lecturer;
            $this->nidn = $this->lecturer->nidn;
            $this->nip = $this->lecturer->nip;
            $this->phoneNumber = $this->lecturer->lecturer_phone_number;
            $this->address = $this->lecturer->lecturer_address;
            $this->lecturerSignature = $this->lecturer->lecturer_signature;
            $this->lecturerQrSignature = $this->lecturer->lecturer_qr_signature;

        } else if ($this->role == 'mahasiswa') {

            $this->student = Auth::user()->student;
            $this->studentClassId = $this->student->student_class_id;
            $this->phoneNumber = $this->student->student_phone_number;
            $this->nim = $this->student->nim;
            $this->address = $this->student->student_address;

            $this->studentClass = StudentClass::all();

        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        if ($this->role == 'admin') {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            ]);
            $user->fill($validated);

        } elseif (in_array($this->role, ['dosenWali', 'kaprodi', 'kajur'])) {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'nidn' => ['required', 'string'],
                'nip' => ['nullable', 'string'],
                'phoneNumber' => ['required', 'string'],
                'address' => ['required', 'string'],
                'lecturerSignature' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ]);

            $signaturePath = $user->lecturer->lecturer_signature;

            if ($this->lecturerSignature instanceof UploadedFile) {
                // Hapus file lama jika ada
                if ($signaturePath && Storage::disk('public')->exists($signaturePath)) {
                    Storage::disk('public')->delete($signaturePath);
                }

                // Simpan file baru
                $signaturePath = $this->lecturerSignature->store('signatures', 'public');

                // //menggunakan ip agar bisa diakses melalui hp
                // $ip = getHostByName(getHostName()); // ganti dengan IP komputer kamu
                // $qrContent = "http://$ip:8000/storage/$signaturePath";

                // $qrImageName = 'qr_signature_'. $user->lecturer->id . '.png';
                // $qrPath = storage_path('app/public/qr/' . $qrImageName);

                // QrCode::format('png')->size(300)->generate($qrContent, $qrPath);

                // ✅ Tambahkan ini supaya langsung update preview-nya
                $this->lecturerSignature = $signaturePath;
            }

            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $user->lecturer->update([
                'nidn' => $validated['nidn'],
                'nip' => $validated['nip'],
                'lecturer_phone_number' => $validated['phoneNumber'],
                'lecturer_address' => $validated['address'],
                'lecturer_signature' => $signaturePath,
                // 'lecturer_qr_signature' => 'qr/' . $qrImageName,
            ]);
        }

            if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('saved', message: 'Berhasil memperbarui profil');
    }


    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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

        @role(['dosenWali', 'kaprodi', 'kajur'])
        <div>
            <x-input-label for="nidn" :value="__('NIDN')" />
            <x-text-input wire:model="nidn" id="nidn" name="nidn" type="text" class="mt-1 block w-full" required autofocus autocomplete="nidn" />
            <x-input-error class="mt-2" :messages="$errors->get('nidn')" />
        </div>
        <div>
            <x-input-label for="phoneNumber" :value="__('Nomor HP')" />
            <x-text-input wire:model="phoneNumber" id="phoneNumber" name="phoneNumber" type="text" class="mt-1 block w-full" required autofocus autocomplete="phoneNumber" />
            <x-input-error class="mt-2" :messages="$errors->get('phoneNumber')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('Alamat')" />
            <x-text-input wire:model="address" id="address" name="address" type="text" class="mt-1 block w-full" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div>
            <x-input-label for="lecturerSignature" :value="__('Tanda Tangan')" />
            <input wire:model.defer="lecturerSignature" id="file_input" type="file"
                class="block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                        cursor-pointer border border-gray-300 rounded-md bg-gray-50 focus:ring focus:ring-blue-200 transition" autofocus autocomplete="lecturerSignature" />
            <x-input-error class="mt-2" :messages="$errors->get('lecturerSignature')" />
            {{-- Preview jika ada tanda tangan --}}
            @if ($lecturerSignature instanceof \Livewire\TemporaryUploadedFile)
                {{-- Preview file baru yang sedang di-upload --}}
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-1">Preview Tanda Tangan (Belum Disimpan):</p>
                    <img src="{{ $lecturerSignature->temporaryUrl() }}" alt="Preview Tanda Tangan"
                        class="h-32 border border-gray-300 rounded-md shadow-sm" />
                </div>
            @elseif(is_string($lecturerSignature))
                {{-- Preview file lama yang sudah tersimpan --}}
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-1">Preview Tanda Tangan:</p>
                    <img src="{{ Storage::url($lecturerSignature) }}" alt="Tanda Tangan Dosen"
                        class="h-32 border border-gray-300 rounded-md shadow-sm" />
                </div>
            @endif

        </div>
        @endrole
        
        @role('mahasiswa')
        
        <div>
            <x-input-label for="phoneNumber" :value="__('Nomor HP')" />
            <x-text-input wire:model="phoneNumber" id="phoneNumber" name="phoneNumber" type="text" class="mt-1 block w-full" required autofocus autocomplete="phoneNumber" />
            <x-input-error class="mt-2" :messages="$errors->get('phoneNumber')" />
        </div>
        <div>
            <x-input-label for="nim" :value="__('NIM')" />
            <x-text-input wire:model="nim" id="nim" name="nim" type="text" class="mt-1 block w-full" required autofocus autocomplete="nim" />
            <x-input-error class="mt-2" :messages="$errors->get('nim')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('Alamat')" />
            <x-text-input wire:model="address" id="address" name="address" type="text" class="mt-1 block w-full" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('Alamat')" />
            <x-text-input wire:model="address" id="address" name="address" type="text" class="mt-1 block w-full" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        @endrole



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
