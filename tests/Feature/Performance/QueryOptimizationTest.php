<?php

declare(strict_types=1);

namespace Tests\Feature\Performance;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\TicketController;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Portfolio;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueryOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user for authentication
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /**
     * Test that Category index endpoint properly eager loads relationships
     */
    public function test_category_index_optimizes_queries(): void
    {
        // Create test data with relationships
        $parentCategory = Category::factory()->create(['title' => 'Parent Category']);
        $childCategory = Category::factory()->create([
            'title' => 'Child Category',
            'parent_id' => $parentCategory->id
        ]);

        // Reset query log
        DB::flushQueryLog();
        DB::enableQueryLog();

        // Make request to index endpoint
        $response = $this->getJson('/api/category');

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'parent',
                    'children_count'
                ]
            ]
        ]);

        // Should have minimal queries due to eager loading
        // Expecting: 1 main query + 1 for relationships (or combined in 1 with joins)
        $this->assertLessThanOrEqual(3, count($queries), 
            "Category index should use minimal queries with eager loading. Actual queries: " . count($queries));
    }

    /**
     * Test that Comment index endpoint properly eager loads relationships
     */
    public function test_comment_index_optimizes_queries(): void
    {
        // Create test data with relationships
        $user = User::factory()->create(['name' => 'Test User']);
        $admin = User::factory()->create(['name' => 'Admin User']);
        
        Comment::factory()->create([
            'comment' => 'Test comment',
            'user_id' => $user->id,
            'admin_id' => $admin->id
        ]);

        // Reset query log
        DB::flushQueryLog();
        DB::enableQueryLog();

        // Make request to index endpoint
        $response = $this->getJson('/api/comment');

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $response->assertStatus(200);

        // Should have minimal queries due to eager loading
        $this->assertLessThanOrEqual(3, count($queries), 
            "Comment index should use minimal queries with eager loading. Actual queries: " . count($queries));
    }

    /**
     * Test that Portfolio index endpoint properly eager loads relationships
     */
    public function test_portfolio_index_optimizes_queries(): void
    {
        // Create test data with relationships
        $category = Category::factory()->create(['title' => 'Portfolio Category']);
        $creator = User::factory()->create(['name' => 'Creator']);
        
        Portfolio::factory()->create([
            'title' => 'Test Portfolio',
            'category_id' => $category->id,
            'creator_id' => $creator->id
        ]);

        // Reset query log
        DB::flushQueryLog();
        DB::enableQueryLog();

        // Make request to index endpoint
        $response = $this->getJson('/api/portfolio');

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $response->assertStatus(200);

        // Should have minimal queries due to eager loading
        $this->assertLessThanOrEqual(4, count($queries), 
            "Portfolio index should use minimal queries with eager loading. Actual queries: " . count($queries));
    }

    /**
     * Test that Ticket index endpoint properly eager loads relationships
     */
    public function test_ticket_index_optimizes_queries(): void
    {
        // Create test data with relationships
        $user = User::factory()->create(['name' => 'Ticket User']);
        
        Ticket::factory()->create([
            'subject' => 'Test Ticket',
            'user_id' => $user->id
        ]);

        // Reset query log
        DB::flushQueryLog();
        DB::enableQueryLog();

        // Make request to index endpoint
        $response = $this->getJson('/api/ticket');

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $response->assertStatus(200);

        // Should have minimal queries due to eager loading
        $this->assertLessThanOrEqual(3, count($queries), 
            "Ticket index should use minimal queries with eager loading. Actual queries: " . count($queries));
    }

    /**
     * Test repository default relationships work correctly
     */
    public function test_repository_default_relationships(): void
    {
        $category = Category::factory()->create(['title' => 'Test Category']);
        $parentCategory = Category::factory()->create(['title' => 'Parent Category']);
        $category->update(['parent_id' => $parentCategory->id]);

        // Test that repository includes default relationships
        $repository = app(\App\Repositories\Category\CategoryRepositoryInterface::class);
        
        DB::flushQueryLog();
        DB::enableQueryLog();
        
        $results = $repository->get();
        
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Should have loaded parent relationship by default
        $this->assertTrue($results->first()->relationLoaded('parent'), 
            'Repository should load default relationships');
    }
}