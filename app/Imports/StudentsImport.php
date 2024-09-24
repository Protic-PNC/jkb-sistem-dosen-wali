<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Student;
use App\Models\StudentClass;


class StudentsImport implements ToCollection, ToModel
{
    private $current = 0;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    }

    public function model(array $row)
    {
        $this->current++;
        if($this->current > 1)
        {
            $class = StudentClass::where('class_name', $row[4])->first();

            $student = new Student;
            $student->student_name = $row[0];
            $student->student_phone_number = $row[1];
            $student->nim = $row[2];
            $student->student_address = $row[3];
            $student->class_id = $class ? $class->class_id : null;
            $student->save();
        }
    }
}
