<?php

namespace Tests\Unit\Http\Controllers\API;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->actingAs($user, 'api')->json('GET', '/invoices')->assertStatus(200)->assertExactJson([]);

        $invoices = factory(\App\Models\Invoice::class, 10)->create()->sortByDesc('document_date')->toArray();
        $data = [];
        foreach ($invoices as $invoice) $data[] = $invoice;

        $this->actingAs($user, 'api')->json('GET', '/invoices')->assertStatus(200)->assertJson($data);
    }

    public function testShow()
    {
        $user = factory(\App\Models\User::class)->create();
        $invoice = factory(\App\Models\Invoice::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', "invoices/$invoice->id")
            ->assertStatus(200)
            ->assertJson($invoice->toArray());

        $this->json('GET', "invoices/2")
            ->assertStatus(404);

    }

    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        $invoice = factory(\App\Models\Invoice::class)->make();
        $data = $invoice->toArray();
        $data['document_types'] = [factory(\App\Models\DocumentType::class)->create()->id];

        $this->actingAs($user, 'api')
            ->json('POST', '/invoices', $data)
            ->assertStatus(201)
            ->assertJsonFragment($invoice->toArray());

        $this->json('POST', '/invoices', [])
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors',
                'message' => 'The given data was invalid.',
            ]);

        $this->assertDatabaseHas('invoices', $invoice->toArray());
    }

    public function testUpdate()
    {
        $user = factory(\App\Models\User::class)->create();
        $invoice = factory(\App\Models\Invoice::class)->create();
        $invoice->note = 'Just a test note.';
        $data = $invoice->toArray();
        $data['document_types'] = [factory(\App\Models\DocumentType::class)->create()->id];

        $this->actingAs($user, 'api')
             ->json('PUT', "/invoices/$invoice->id", $data)
             ->assertStatus(200)
             ->assertJsonFragment(['note' => 'Just a test note.']);

        $this->json('PUT', "/invoices/$invoice->id", [])
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors',
                'message' => 'The given data was invalid.',
            ]);

        $this->json('PUT', '/invoices/2', $data)->assertStatus(404);

        $this->assertDatabaseHas('invoices', ['note' => 'Just a test note.']);
    }

    public function testUploadFile()
    {
        Storage::fake('test');

        $user = factory(\App\Models\User::class)->create();
        $invoice = factory(\App\Models\Invoice::class)->create();
        $file = UploadedFile::fake()->create('invoice.pdf');

        $this->actingAs($user, 'api')
            ->json('POST', "/invoices/$invoice->id/files", [
                'file' => $file,
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
            ]);

        // Storage::disk('test')->assertExists($file->path());
    }

    public function testRemoveUploadedFile()
    {
        Storage::fake('test');

        $user = factory(\App\Models\User::class)->create();
        $invoice = factory(\App\Models\Invoice::class)->create();
        $file = UploadedFile::fake()->create('invoice.pdf');

        $this->actingAs($user, 'api')
            ->json('POST', "/invoices/$invoice->id/files", [
                'file' => $file,
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 2,
            ]);

        // Storage::disk('test')->assertExists($file->path());

        $this->json('DELETE', "/invoices/$invoice->id/files/2")
            ->assertStatus(204);

        // Storage::disk('test')->assertMissing($file->path());
    }

    public function testDestroy()
    {
        $user = factory(\App\Models\User::class)->create();
        $invoice = factory(\App\Models\Invoice::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', "/invoices/$invoice->id")
            ->assertStatus(204);
    }
}
