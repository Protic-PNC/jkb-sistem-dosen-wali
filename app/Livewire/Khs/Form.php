<?php

namespace App\Livewire\Khs;

use App\Models\Khs;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;
use Illuminate\Http\UploadedFile;

use App\Helpers\PdfHelper;

class Form extends Component
{
    use WithFileUploads;

    public $formTitle;
    public $existingFile;
    public $isEdit = false;
    public $showModal = false;
    public $message;
    public $createMassal = false;

    public $students;
    public $student;
    public $studentId;
    public $studentClass;
    public $studentClasses;
    public $studentClassId;
    public $semester;
    public $maxSemester;
    public $file;
    public $khsId;
    public $khs;
    public $usedSemesters;

    public function mount()
    {
        $this->studentClasses = StudentClass::all();
        // $this->students = Student::all();
    }

    public function updatedStudentClassId($value)
    {
        $this->studentClassId = $value;

        if ($this->studentClassId) {
            $this->studentClass = StudentClass::find($this->studentClassId);
            $this->maxSemester = match ($this->studentClass->program->degree) {
                'D3' => 6,
                'D4' => 8,
            };
            $this->students = Student::where('student_class_id', $this->studentClassId)->get();
        }
    }

    public function updatedStudentId($value)
    {
        $this->student = Student::find($value);

        $this->maxSemester = match ($this->student->student_class->program->degree) {
            'D3' => 6,
            'D4' => 8,
        };

        $this->khs = KHS::with('student')->where('student_id', $this->student->id)->get();
        $this->usedSemesters = $this->khs->pluck('semester')->toArray();
    }

    public function rules()
    {
        return [
            
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'studentClassId',
            'semester',
            'file',
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    #[On('create')]
    public function create()
    {
        $this->resetValidation();
        $this->resetForm();
        
        $this->formTitle = 'Tambah KHS';
        $this->existingFile = false;
        $this->isEdit = false;
        $this->showModal = true;
        $this->createMassal = false;
    }

    #[On('createMassal')]
    public function createMassal()
    {
        $this->resetValidation();
        $this->resetForm();

        $this->formTitle = 'Tambah KHS';
        $this->existingFile = false;
        $this->isEdit = false;
        $this->showModal = true;
        $this->createMassal = true;
    }

    #[On('edit-khs')]
    public function edit($id)
    {
        $this->resetValidation();
        $this->resetForm();

        $this->formTitle = 'Edit KHS';
        $this->khsId = $id;
        $this->khs = Khs::find($this->khsId);

        $this->existingFile = $this->khs->file;
        $this->studentClassId = $this->khs->student->student_class_id;
        $this->studentId = $this->khs->student_id;
        $this->semester = $this->khs->semester;
        $this->updatedStudentClassId($this->studentClassId);
        $this->updatedStudentId($this->studentId);

        $this->isEdit = true;
        $this->showModal = true;
        $this->createMassal = false;
    }

    public function save()
    {
        if (!$this->isEdit) {
            if (!$this->createMassal) {
                $data = $this->validate([
                    'file' => 'required|file|mimes:pdf|max:51200',
                    'semester' => 'required|numeric|min:1|max:10',
                    'studentId' => 'required|exists:students,id',
                ], [
                    'file.required' => 'File KHS wajib diunggah.',
                    'file.mimes' => 'File harus dalam format .pdf.',
                    'semester.required' => 'Semester harus dipilih.',
                    'semester.numeric' => 'Semester harus berupa angka.',
                    'studentId.required' => 'Mahasiswa harus dipilih.',
                    'studentId.exists' => 'Mahasiswa tidak ditemukan.',
                ]);


                // Dapatkan NIM mahasiswa
                $student = \App\Models\Student::find($this->studentId);
                if (!$student) {
                    $this->addError('studentId', 'Mahasiswa tidak ditemukan.');
                    return;
                }

                if ($this->file) {
                    // Format nama file: NIM_Semester.pdf
                    $filename = $student->nim . '_' . $this->semester . '.pdf';

                    // Simpan file ke public storage/khs dengan nama tersebut
                    $data['file'] = $this->file->storeAs('khs', $filename, 'public');
                } elseif ($this->existingFile) {
                    $data['file'] = $this->existingFile;
                }

                if (!$this->khsId) {
                    Khs::create([
                        'student_id' => $data['studentId'],
                        'semester' => $data['semester'],
                        'file' => $data['file'],
                    ]);
                    $this->message = 'KHS berhasil ditambahkan.';
                    // Khs::find($this->khsId)->update([
                    //     'student_id' => $data['studentId'],
                    //     'semester' => $data['semester'],
                    //     'file' => $data['file'],
                    // ]);
                    // $this->message = 'KHS berhasil diubah.';
                }
            } else {
                $this->validate([
                    'file' => 'file|mimes:pdf|max:51200',
                    'semester' => 'required|numeric|min:1|max:10',
                    'studentClassId' => 'required|exists:student_classes,id',
                ], [
                    'file.required' => 'File PDF KHS wajib diunggah.',
                    'file.mimes' => 'File harus berformat .pdf.',
                    'semester.required' => 'Semester wajib diisi.',
                    'studentClassId.required' => 'Kelas mahasiswa harus dipilih.',
                ]);


                $studentClass = StudentClass::findOrFail($this->studentClassId);
                $entryYear = $studentClass->entry_year;
                $semester = (int) $this->semester;

                // ===== Menentukan $startDate dan $endDate berdasarkan semester ganjil/genap =====
                $tahun = $entryYear + intdiv($semester, 2);

                if ($semester % 2 == 1) {
                    // Semester ganjil: Agustus - Januari tahun berikutnya
                    $bulanAwal = 8;
                    $bulanAkhir = 1;
                    $tahunAkhir = $tahun + 1;
                } else {
                    // Semester genap: Februari - Juli
                    $bulanAwal = 2;
                    $bulanAkhir = 7;
                    $tahunAkhir = $tahun;
                }

                // $startDate = \Carbon\Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
                $endDate = \Carbon\Carbon::create($tahunAkhir, $bulanAkhir, 1)->endOfMonth();


                $students = Student::with('user')
                    ->where('student_class_id', $studentClass->id)
                    ->where(function ($q) use ($endDate) {
                        $q->whereNull('inactive_at')
                        ->orWhere('inactive_at', '>', $endDate);
                    })
                    ->get()
                    ->sortBy(fn($student) => $student->user->name ?? '')
                    ->values();

                    // dd(collect($students)->pluck('user.name')->toArray());
                if ($students->isEmpty()) {
                    $this->addError('file', 'Tidak ada mahasiswa aktif untuk kelas dan semester ini.');
                    return;
                }

                // Simpan PDF upload ke folder public/khs
                $filePath = $this->file->store('khs', 'public');
                $fullPath = storage_path('app/public/' . $filePath);
                $outputDir = storage_path('app/temp_split_khs');

                // Pastikan file benar-benar tersimpan
                if (!file_exists($fullPath)) {
                    $this->addError('file', 'File gagal disimpan.');
                    return;
                }

                // Bagi file PDF menjadi satu halaman per mahasiswa
                $splitFiles = collect(PdfHelper::splitPdf($fullPath, $outputDir))
                ->sortBy(function ($path) {
                    // Ambil nomor halaman dari nama file: page_1.pdf, page_2.pdf, dst.
                    preg_match('/page_(\d+)\.pdf$/', $path, $matches);
                    return isset($matches[1]) ? (int) $matches[1] : 9999;
                })
                ->values();

                if ($splitFiles->count() !== $students->count()) {
                    $this->addError('file', "Jumlah halaman PDF tidak cocok dengan jumlah mahasiswa (PDF: {$splitFiles->count()}, Mahasiswa: {$students->count()}).");

                    // Hapus hasil split yang tidak valid
                    Storage::deleteDirectory('temp_split_khs');
                    Storage::disk('public')->delete($filePath); // Hapus PDF utama
                    return;
                }

                // Simpan setiap file hasil split ke folder public/khs
                foreach ($students as $index => $student) {
                    $pagePath = $splitFiles[$index];
                    $fileName = "khs_{$student->nim}_{$semester}.pdf";
                    $storagePath = "khs/{$fileName}";

                    if (!file_exists($pagePath)) {
                        continue; // Skip jika file tidak valid
                    }

                    Storage::disk('public')->put($storagePath, file_get_contents($pagePath));

                    Khs::create([
                        'student_id' => $student->id,
                        'semester' => $semester,
                        'file' => $storagePath,
                    ]);
                }

                // Bersihkan file sementara
                Storage::deleteDirectory('temp_split_khs');
                Storage::disk('public')->delete($filePath); // Hapus PDF utama setelah diproses

                $this->message = 'KHS berhasil diunggah untuk semua mahasiswa.';
            }
        } else {
            $this->validate([
                    'file' => 'nullable|file|mimes:pdf|max:51200',
                    'semester' => 'required|numeric|min:1|max:10',
                    'studentClassId' => 'required|exists:student_classes,id',
                ], [
                    'file.mimes' => 'File harus berformat .pdf.',
                    'semester.required' => 'Semester wajib diisi.',
                    'studentClassId.required' => 'Kelas mahasiswa harus dipilih.',
                ]);
            
            if (!empty($this->file)) {
                if ($this->isEdit) {
                    $khsOld = Khs::find($this->khsId);

                    if ($khsOld && $khsOld->file && Storage::disk('public')->exists($khsOld->file)) {
                        Storage::disk('public')->delete($khsOld->file);
                    }
                }

                $filePath = $this->file->store('khs', 'public');
            } else {
                $filePath = $this->existingFile;
            }
            
            $this->khs = Khs::find($this->khsId);

            $this->khs->update([
                'student_id' => $this->studentId,
                'semester' => $this->semester,
                'file' => $filePath,
            ]);

            $this->message = 'KHS berhasil diubah';
        }

        $this->student = null;
        $this->dispatch('saved', message: $this->message);
        $this->showModal = false;
        $this->resetValidation();
    }
    
    #[On('delete-khs')]
    public function delete($id)
    {
        $this->khs = Khs::find($id);

        $filePath = $this->khs->file;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        
        $this->khs->delete();
        $this->dispatch('saved', message: 'KHS berhasil dihapus.');
    }

    #[On('delete-multiple-khs')]
    public function deleteMultiple(array $ids)
    {
        foreach ($ids as $id) {
            $khs = Khs::find($id);
            if ($khs) {
                if (Storage::disk('public')->exists($khs->file)) {
                    Storage::disk('public')->delete($khs->file);
                }
                $khs->delete();
            }
        }

        $this->dispatch('saved', message: 'KHS yang dipilih berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.khs.form');
    }
}
