<?php

namespace Tests\Unit;

use App\Models\Media;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_project(): void
    {
        $project = Project::factory()->create();
        $media = Media::factory()->create(['project_id' => $project->id]);

        $this->assertInstanceOf(Project::class, $media->project);
        $this->assertEquals($project->id, $media->project->id);
    }

    #[Test]
    public function it_belongs_to_an_uploader(): void
    {
        $uploader = User::factory()->create();
        $media = Media::factory()->create(['uploaded_by' => $uploader->id]);

        $this->assertInstanceOf(User::class, $media->uploader);
        $this->assertEquals($uploader->id, $media->uploader->id);
    }

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $project = Project::factory()->create();
        $uploader = User::factory()->create();

        $media = Media::create([
            'project_id' => $project->id,
            'file_path' => 'uploads/photo.jpg',
            'file_name' => 'photo.jpg',
            'title' => 'Test Photo',
            'uploaded_by' => $uploader->id,
            'media_type' => 'image',
        ]);

        $this->assertEquals('uploads/photo.jpg', $media->file_path);
        $this->assertEquals('photo.jpg', $media->file_name);
        $this->assertEquals('Test Photo', $media->title);
    }

    #[Test]
    public function it_can_be_created_with_factory(): void
    {
        $media = Media::factory()->create();

        $this->assertNotNull($media->id);
        $this->assertNotNull($media->file_path);
        $this->assertNotNull($media->media_type);
    }

    #[Test]
    public function filter_scope_works(): void
    {
        $media1 = Media::factory()->create(['title' => 'Searchable Title']);
        $media2 = Media::factory()->create(['title' => 'Other Title']);

        $filtered = Media::filter(['search' => 'Searchable'])->get();

        $this->assertCount(1, $filtered);
        $this->assertEquals($media1->id, $filtered->first()->id);
    }
}
