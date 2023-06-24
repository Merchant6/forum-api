<?php

namespace App\Interfaces;

use Illuminate\Support\Facades\Request;

/**
 * Interface for PostRepository
 */
interface RepositoryInterface
{
    public function all();

    public function store($data);

    public function show(string $slug);

    public function update(string $id, $requestData);

    public function destroy(string $id);
}