<?php

namespace App\Repositories;

interface CourseRepositoryInterface
{
    public function all();
    public function find($id);
    public function addNewCourse(array $data);
    public function update($id, array $data);
    public function delete($id);
}
