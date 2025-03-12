<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    public function all()
    {
        return Tag::all();
    }

    public function find($id)
    {
        return Tag::find($id);
    }

    public function create(array $data)
    {
        return Tag::create($data);
    }

    public function update($id, array $data)
    {
        $Tag = Tag::find($id);
        return $Tag->update($data);
    }

    public function delete($id)
    {
        return Tag::destroy($id);
    }
}

