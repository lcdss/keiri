<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('migrate-old-data', function () {
    $connection = $this->ask('Connection name:');

    $this->call('migrate:fresh');
    $this->call('db:seed');

    $this->comment("Migrating from $connection connection...");

    $invoices_old = DB::connection($connection)->table('invoices')->get();
    $count = count($invoices_old);
    $this->comment("$count transactions will be migrated");

    $people_old = DB::connection($connection)->table('people')->get();
    $count = count($people_old);
    $this->comment("$count people will be migrated");

    $references_old = DB::connection($connection)->table('references')->get();
    $count = count($references_old);
    $this->comment("$count tags will be migrated");

    $companies_old = DB::connection($connection)->table('companies')->get();
    $count = count($companies_old);
    $this->comment("$count companies will be migrated");

    $categories_old = DB::connection($connection)->table('categories')->get();
    $count = count($categories_old);
    $this->comment("$count categories will be migrated");

    $files_old = DB::connection($connection)->table('files')->get();
    $count = count($files_old);
    $this->comment("$count media files will be migrated");

    $this->comment('Migrating people...');
    $people_old->each(function ($record) {
        $person = new App\Models\Person();
        $person->id = $record->id;
        $person->name = $record->name;
        $person->created_at = $record->created_at;
        $person->updated_at = $record->updated_at;
        $person->save();
    });
    $this->comment('People migrated.');

    $this->comment('Migrating companies...');
    $companies_old->each(function ($record) {
        $company = new App\Models\Company();
        $company->id = $record->id;
        $company->name = $record->name;
        $company->created_at = $record->created_at;
        $company->updated_at = $record->updated_at;
        $company->save();
    });
    $this->comment('Companies migrated.');

    $this->comment('Migrating tags...');
    $references_old->each(function ($record) {
        $tag = new App\Models\Tag();
        $tag->id = $record->id;
        $tag->name = $record->name;
        $tag->created_at = $record->created_at;
        $tag->updated_at = $record->updated_at;
        $tag->save();
    });
    $this->comment('Tags migrated.');

    $this->comment('Migrating categories...');
    $categories_old->each(function ($record) {
        $category = new App\Models\Category();
        $category->id = $record->id;
        $category->name = $record->name;
        $category->_lft = $record->_lft;
        $category->_rgt = $record->_rgt;
        $category->parent_id = $record->parent_id;
        $category->save();
    });
    $this->comment('Categories migrated.');

    $this->comment('Migrating transactions...');
    $invoices_old->each(function ($record) use ($connection) {
        $document_types_id = [];
        $document_types_old = DB::connection($connection)->table('document_types')
            ->join('document_type_invoice', 'document_types.id', '=', 'document_type_invoice.document_type_id')
            ->where('document_type_invoice.invoice_id', $record->id)
            ->pluck('document_types.name');

        foreach ($document_types_old as $document_type) {
            $tag = App\Models\Tag::where('name', $document_type)->firstOrCreate(['name' => $document_type]);
            array_push($document_types_id, $tag->id);
        }

        $transaction = new App\Models\Transaction();
        $transaction->id = $record->id;
        $transaction->value = $record->operation_type === 'I' ? $record->amount : -$record->amount;
        $transaction->note = $record->note;
        $transaction->issue_date = $record->document_date;
        $transaction->due_date = $record->payment_date;
        $transaction->payment_date = $record->payment_date;
        $transaction->paid = $record->payment_date !== null;
        $transaction->user_id = 1;
        $transaction->person_id = $record->person_id;
        $transaction->company_id = $record->company_id;
        $transaction->category_id = $record->category_id;
        $transaction->tags()->attach($record->reference_id);
        $transaction->created_at = $record->document_date;
        $transaction->updated_at = $record->updated_at;
        $transaction->save();

        $files_old = DB::connection($connection)->table('files')->where('attachment_id', $record->id)->get();

        foreach ($files_old as $index => $item) {
            $media = $transaction->addMedia(storage_path("app/$item->path"))->toMediaCollection('transactions');
            if ($index === 0) $media->tags()->attach($document_types_id);
        }
    });
    $this->comment('Transactions migrated.');

    $this->comment('Migration finished');
})->describe('Migrate data from the old database model');
