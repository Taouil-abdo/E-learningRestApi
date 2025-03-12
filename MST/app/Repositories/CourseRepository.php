<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
    public function all()
    {
        return Course::all();
    }

    public function find($id)
    {
        return Course::find($id);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function update($id, array $data)
    {
        $Course = Course::find($id);
        return $Course->update($data);
    }

    public function delete($id)
    {
        return Course::destroy($id);
    }
}

