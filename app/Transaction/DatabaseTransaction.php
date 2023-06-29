<?php

use BRCas\CA\UseCase\DatabaseTransactionInterface;
use Illuminate\Support\Facades\DB;

class DatabaseTransaction implements DatabaseTransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollback();
    }
}
