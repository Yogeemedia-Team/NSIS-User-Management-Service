<?php

namespace App\Repositories;

use Exception;
use App\Interfaces\DBPreparableInterface;
use App\Models\YearGradeClass;
use App\Interfaces\YearGradeClassInterface ;
use Illuminate\Http\Response;


class YearGradeClassRepository implements YearGradeClassInterface, DBPreparableInterface {
    public function getAll(array $filterData)
    {
        $filter = $this->getFilterData($filterData);
        $query = YearGradeClass::with('grade', 'class')->get(); 
        // $query = YearGradeClass::orderBy($filter['orderBy'], $filter['order']);

        // if (!empty($filter['search'])) {
        //     $query->where(function ($query) use ($filter) {
        //         $searched = '%' . $filter['search'] . '%';
        //         $query->where('level', 'like', $searched)
        //         ->orWhere('title', 'like', $searched);
        //     });
        // }
        return $query;
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

    public function getById(int $id): ?YearGradeClass
    {
        $year_grade_class = YearGradeClass::find($id);

        if (empty($year_grade_class)) {
            throw new Exception("Year grade class relation does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $year_grade_class;
    }

    public function create(array $data): ?YearGradeClass
    {
        $data = $this->prepareForDB($data);

        return YearGradeClass::create($data);
    }

    public function update(int $id, array $data): ?YearGradeClass
    {
        $year_grade_class = $this->getById($id);

        $updated = $year_grade_class->update($this->prepareForDB($data, $year_grade_class));

        if ($updated) {
            $year_grade_class = $this->getById($id);
        }

        return $year_grade_class;
    }

    public function delete(int $id): ?YearGradeClass
    {
        $year_grade_class = $this->getById($id);
        $deleted = $year_grade_class->delete();

        if (!$deleted) {
            throw new Exception("Year grade class relation could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $year_grade_class;
    }

    public function prepareForDB(array $data, ?YearGradeClass $year_grade_class = null): array
    {
        return [
            'year' => $data['year'],
            'organization_id' => $data['organization_id'],
            'master_grade_id' => $data['master_grade_id'],
            'master_class_id' => $data['master_class_id'],
            'active_status' => $data['active_status'],

        ];
    }

    




}