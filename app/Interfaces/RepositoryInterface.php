<?php

namespace App\Interfaces;

use Illuminate\Support\Facades\Request;

/**
 * Interface for PostRepository
 */
interface RepositoryInterface
{
    public function all();

    public function store();

    public function show(string $id);

    public function update(Request $request, string $id);

    public function destroy(string $id);
}