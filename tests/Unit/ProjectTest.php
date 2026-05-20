<?php

namespace Tests\Unit;

use App\Models\Media;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_media_items(): void
    {
        $project = Project::factory()->create();
        $media1 = Media::factory()->create(['project_id' => $project->id]);
        $media2 = Media::factory()->create(['project_id' => $project->id]);

        $this->assertCount(2, $project->media);
        $this->assertTrue($project->media->contains($media1));
        $this->assertTrue($project->media->contains($media2));
    }

    #[Test]
    public function it_can_have_a_hero_media(): void
    {
        $project = Project::factory()->create();
        $heroMedia = Media::factory()->create();
        $project->update(['hero_media_id' => $heroMedia->id]);

        $this->assertInstanceOf(Media::class, $project->hero);
        $this->assertEquals($heroMedia->id, $project->hero->id);
    }

    #[Test]
    public function it_is_not_featured_by_default(): void
    {
        $project = Project::factory()->create(['is_featured' => false]);

        $this->assertFalse($project->is_featured);
    }

    #[Test]
    public function it_can_be_featured(): void
    {
        $project = Project::factory()->create(['is_featured' => true]);

        $this->assertTrue($project->is_featured);
    }

    #[Test]
    public function is_featured_is_cast_to_boolean(): void
    {
        $project = Project::factory()->create(['is_featured' => true]);

        $this->assertTrue($project->is_featured);
    }

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $project = Project::create([
            'title' => 'Wedding Portfolio',
            'slug' => 'wedding-portfolio',
            'description' => 'Beautiful wedding photos',
            'is_featured' => true,
        ]);

        $this->assertEquals('Wedding Portfolio', $project->title);
        $this->assertEquals('wedding-portfolio', $project->slug);
        $this->assertEquals('Beautiful wedding photos', $project->description);
    }

    #[Test]
    public function published_scope_filters_featured_projects(): void
    {
        Project::factory()->create(['is_featured' => true]);
        Project::factory()->create(['is_featured' => false]);

        $published = Project::published()->get();

        $this->assertCount(1, $published);
        $this->assertEquals(true, (bool) $published->first()->is_featured);
    }
}
