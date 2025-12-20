<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PatientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['name']) || empty($row['phone'])) {
            return null;
        }

        $dateOfBirth = null;
        if (isset($row['date_of_birth']) && !empty($row['date_of_birth'])) {
            try {
                if (is_numeric($row['date_of_birth'])) {
                    $dateOfBirth = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d');
                } else {
                    $dateOfBirth = date('Y-m-d', strtotime($row['date_of_birth']));
                }
            } catch (\Exception $e) {
                $dateOfBirth = null;
            }
        }

        $phone = (string)$row['phone'];
        $splitName = $this->splitFullName($row['name']);

        $fileNumber = isset($row['file_number']) && !empty($row['file_number'])
            ? (int)$row['file_number']
            : ((int)(Patient::max('file_number') ?? 0) + 1);

        return new Patient([
            'file_number' => $fileNumber,
            'first_name' => $splitName['first_name'],
            'middle_name' => $splitName['middle_name'] ?? null,
            'last_name' => $splitName['last_name'] ?? 'Undefined',
            'phone' => $phone,
            'email' => $row['email'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'gender' => (string)$row['gender'] ?? 'male',
            'address' => $row['address'] ?? null,
            'category' => 'normal',
            'type' => (string)$row['patient_type'] ?? 'cash',
        ]);

    }

    function splitFullName($fullName)
    {
        $nameParts = array_filter(explode(' ', trim($fullName)));
        $count = count($nameParts);

        if ($count === 0) {
            return [
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null
            ];
        }

        if ($count === 1) {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => null,
                'last_name' => null
            ];
        }

        if ($count === 2) {
            return [
                'first_name' => $nameParts[0],
                'middle_name' => null,
                'last_name' => $nameParts[1]
            ];
        }

        // 3 or more parts
        $firstName = array_shift($nameParts);
        $lastName = array_pop($nameParts);
        $middleName = implode(' ', $nameParts);

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName ?: null,
            'last_name' => $lastName
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required',
        ];
    }
}

