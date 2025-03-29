<?php

namespace App\Repositories;

interface VideoRepositoryInterface
{
    public function create(array $data);
    public function getAllByCourseId($courseId);
    public function getById($id);
    public function update($id, array $data);
    public function delete($id);
}
