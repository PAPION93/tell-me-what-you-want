<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Restaurant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageApiUnitTest extends TestCase
{
    public function tearDown(): void
    {
    }

    /** @test */
    public function it_can_create_an_png()
    {
        $restaurant = factory(Restaurant::class)->create();
        $file = UploadedFile::fake()->image('image.png');

        $data = [
            'restaurant_id' => $restaurant->id,
            'image' => $file,
        ];

        $response = $this->json('POST', '/api/v1/images', $data);
        $response->assertStatus(201);
        Storage::assertExists('public/' . $file->hashName());
    }

    /** @ignore */
    public function it_can_create_an_jpg()
    {
        $restaurant = factory(Restaurant::class)->create();
        $file = UploadedFile::fake()->image('image.jpg');

        $data = [
            'restaurant_id' => $restaurant->id,
            'image' => $file,
        ];

        $response = $this->json('POST', '/api/v1/images', $data);
        $response->assertStatus(201);
        Storage::assertExists('public/' . $file->hashName());
    }

    /** @ignore */
    public function it_can_show_image()
    {
        $file = UploadedFile::fake()->image('image.jpg');
        $file->store('public');
        $this->get('/api/v1/images/' . $file->hashName())
            ->assertOk();
        Storage::assertExists('public/' . $file->hashName());
    }
}
