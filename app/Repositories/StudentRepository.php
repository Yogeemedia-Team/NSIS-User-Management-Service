<?php

namespace App\Repositories;

use Exception;
use App\Interfaces\DBPreparableInterface;
use App\Interfaces\StudentInterface;
use App\Models\StudentDetail;
use App\Models\StudentDocument;
use App\Models\StudentParent;
use App\Models\StudentSibling;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class StudentRepository implements StudentInterface, DBPreparableInterface {
    public function getAll(array $filterData)
    {
        $filter = $this->getFilterData($filterData);

// $collection = collect(StudentDetail::join('student_parents', 'student_details.student_id', '=', 'student_parents.student_id')
//     ->join('student_siblings', 'student_details.student_id', '=', 'student_siblings.student_id')
//     ->join('student_documents', 'student_details.student_id', '=', 'student_documents.student_id')
//     ->select(
//         'student_details.*',
//         'student_parents.*',
//         'student_siblings.*',
//         'student_documents.*'
//     )
//     ->get()
//     ->toArray());


// foreach ($collection as $item) {
//     $studentDetail = [
//         'id' => $item['id'],
//         'student_id' => $item['student_id'],
//         'organization_id' => $item['organization_id'],
//         'admission_no' => $item['sd_admission_no'],
//         'year_grade_class_id' => $item['sd_year_grade_class_id'],
//         'first_name' => $item['sd_first_name'],
//         'last_name' => $item['sd_last_name'],
//         'name_with_initials' => $item['sd_name_with_initials'],
//         'name_in_full' => $item['sd_name_in_full'],
//         'address_line1' => $item['sd_address_line1'],
//         'address_line2' => $item['sd_address_line2'],
//         'address_city' => $item['sd_address_city'],
//         'telephone_residence' => $item['sd_telephone_residence'],
//         'telephone_mobile' => $item['sd_telephone_mobile'],
//         'telephone_whatsapp' => $item['sd_telephone_whatsapp'],
//         'email_address' => $item['sd_email_address'],
//         'gender' => $item['sd_gender'],
//         'date_of_birth' => $item['sd_date_of_birth'],
//         'religion' => $item['sd_religion'],
//         'ethnicity' => $item['sd_ethnicity'],
//         'birth_certificate_number' => $item['sd_birth_certificate_number'],
//         'profile_picture_path' => $item['sd_profile_picture'],
//         'health_conditions' => $item['sd_health_conditions'],
//         'admission_date' => $item['sd_admission_date'],
//         'admission_payment_amount' => $item['sd_admission_payment_amount'],
//         'no_of_installments' => $item['sd_no_of_installments'],
//         'admission_status' => $item['sd_admission_status'],
//         'school_fee' => $item['sd_school_fee'],
//         'total_due' => $item['sd_total_due'],
//         'payment_status' => $item['sd_payment_status'],
//         'academic_status' => $item['sd_academic_status'],
//         'updated_at' => $item['updated_at'],
//         'created_at' => $item['created_at'],
//     ];

//     // $studentParent = [
//     //     'student_id' => $item['student_id'],
//     //     'organization_id' => $item['organization_id'],
//     //     'first_name' => $item['sp_first_name'],
//     //     'last_name' => $item['sp_last_name'],
//     //     'relationship' => $item['sp_relationship'],
//     //     'nic' => $item['sp_nic'],
//     //     'higher_education_qualification' => $item['higher_education_qualification'],
//     //     'occupation' => $item['occupation'],
//     //     'official_address' => $item['official_address'],
//     //     'permanent_address' => $item['permanent_address'],
//     //     'contact_official' => $item['contact_official'],
//     //     'contact_mobile' => $item['contact_mobile'],
//     //     'updated_at' => $item['updated_at'],
//     //     'created_at' => $item['created_at'],
//     //     'id' => $item['id'],
//     // ];

//     // $studentSibling = [
//     //     'student_id' => $item['student_id'],
//     //     'organization_id' => $item['organization_id'],
//     //     'first_name' => $item['first_name'],
//     //     'last_name' => $item['last_name'],
//     //     'gender' => $item['gender'],
//     //     'date_of_birth' => $item['date_of_birth'],
//     //     'school' => $item['school'],
//     //     'updated_at' => $item['updated_at'],
//     //     'created_at' => $item['created_at'],
//     //     'id' => $item['id'],
//     // ];

//     // $studentDocument = [
//     //     'student_id' => $item['student_id'],
//     //     'organization_id' => $item['organization_id'],
//     //     'profile_picture' => $item['profile_picture'],
//     //     'birth_certificate' => $item['birth_certificate'],
//     //     'nic_father' => $item['nic_father'],
//     //     'nic_mother' => $item['nic_mother'],
//     //     'marriage_certificate' => $item['marriage_certificate'],
//     //     'permission_letter' => $item['permission_letter'],
//     //     'leaving_certificate' => $item['leaving_certificate'],
//     //     'updated_at' => $item['updated_at'],
//     //     'created_at' => $item['created_at'],
//     //     'id' => $item['id'],
//     // ];

//     $data['studentDetail'] = $studentDetail;
//     // $data['studentParent'] = $studentParent;
//     // $data['studentSibling'] = $studentSibling;
//     // $data['studentDocument'] = $studentDocument;
// }

// return $data;

    return  $permissions = StudentDetail::where('sd_academic_status', 1)->get();

        
        //return  $permissions = StudentDetail::get();
    }

    public function getFilterData(array $filterData): array
    {
        $defaultArgs = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];

        return array_merge($defaultArgs, $filterData);
    }

    public function getById($id): ?StudentDetail
    {
       $student = StudentDetail::with('parent_data')
        ->with('sibling_data')
        ->with('documents')
        ->with(['year_class_data' => function ($query) {
            $query->with(['grade', 'class']);
        }])
        ->where('student_id', $id)
        ->first();

        if (empty($student)) {
            throw new Exception("User student does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $student;
    }

    public function create(array $data): ?object 
{
    $studentDetail = StudentDetail::create($data);
    $studentParent = StudentParent::create($data);
    $studentSibling = StudentSibling::create($data);
    $studentDocument = StudentDocument::create($data);

    // Check if any of the models is null
    if ($studentDetail === null || $studentParent === null || $studentSibling === null || $studentDocument === null) {
        return null;
    }

    $collection = collect([
        'studentDetail' => $studentDetail,
        'studentParent' => $studentParent,
        'studentSibling' => $studentSibling,
        'studentDocument' => $studentDocument,
    ]);

    return $collection;
}

public function update(array $data, $studentId): ?object 
{
    // Fetch existing records
    $studentDetails = StudentDetail::where('student_id',$studentId)->first();
    $studentParents = StudentParent::where('student_id',$studentId)->first();
    $studentSiblings = StudentSibling::where('student_id',$studentId)->first();
    $studentDocuments = StudentDocument::where('student_id',$studentId)->first();
    
    
    $studentDetail = StudentDetail::find($studentDetails->id);
    $studentParent = StudentParent::find($studentParents->id);
    $studentSibling = StudentSibling::find($studentSiblings->id);
    $studentDocument = StudentDocument::find($studentDocuments->id);


    // Check if any of the models is null
    if ($studentDetail === null || $studentParent === null || $studentSibling === null || $studentDocument === null) {
        return null;
    }

    // Update the existing records with the new data
    $studentDetail->update($data);
    $studentParent->update($data);
    $studentSibling->update($data);
    $studentDocument->update($data);

    // Fetch the updated records (optional, depending on your needs)
    $studentDetail = StudentDetail::find($studentDetails->id);
    $studentParent = StudentParent::find($studentParents->id);
    $studentSibling = StudentSibling::find($studentSiblings->id);
    $studentDocument = StudentDocument::find($studentDocuments->id);

    $collection = collect([
        'studentDetail' => $studentDetail,
        'studentParent' => $studentParent,
        'studentSibling' => $studentSibling,
        'studentDocument' => $studentDocument,
    ]);

    return $collection;
}



     public function delete($id): ?StudentDetail
        {
            $student = StudentDetail::where('student_id', $id)->first();
        
            if (!$student) {
                throw new Exception("User student could not be found.", Response::HTTP_NOT_FOUND);
            }
        
            // Update the student's academic status
            $updateResult = StudentDetail::where('id', $student->id)->update(['sd_academic_status' => 0]);
        
            if ($updateResult === false) {
                throw new Exception("Failed to update academic status.", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        
            // Return the deleted student instance
            return $student;
        }

    public function prepareForDB(array $data, ?StudentDetail $student = null): array
    {
        if (empty($data['student_id'])) {
            $data['student_id'] = $this->createUniqueSlug($data['student_id']);
        }
        return $data;
    }

    private function createUniqueSlug(string $title): string
    {
        return Str::slug(substr($title, 0, 80)) . '-' . time();
    }




}