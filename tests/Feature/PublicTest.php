<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PublicTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /**
     * A basic test example.
     */
    public function testWelcome()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewHas('users', User::count())
            ->assertViewHas('projects', Project::count());
    }

    /**
     * Check redirect to /login when going to the /home page.
     */
    public function testHomeRedirect()
    {
        $response = $this->get('/home');
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * Check JSON request Unauthenticated . .
     */
    public function testJsonRedirect()
    {
        $response = $this->json('GET', '/home');
        $response->assertStatus(401)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJsonModelNotFound()
    {
        $this->expectException(ModelNotFoundException::class);
        // TODO catch this and return 404 ??
        $response = $this->json('GET', '/eggs/get/something/json');
        $response->assertStatus(404)
            ->assertExactJson(["message" => "No releases found"]);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJson()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();

        $response = $this->json('GET', '/eggs/get/'.$version->project->slug.'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                "description" => "",
                "name" => $version->project->name,
                "info" => ["version" => "1"],
                "category" => "uncategorised",
                "releases" => [
                    "1" => [
                        [
                            "url" => url("some_path.tar.gz")
                        ],
                    ]
                ]
            ]);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJson404()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();

        $response = $this->json('GET', '/eggs/get/'.$version->project->slug.'/json');
        $response->assertStatus(404)
            ->assertExactJson(["message" => "No releases found"]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testProjectListJson()
    {
        $response = $this->json('GET', '/eggs/list/json');
        $response->assertStatus(200)->assertExactJson([]);
        $category = factory(Category::class)->create();

        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        factory(File::class)->create(['version_id' => $version->id]);

        $response = $this->json('GET', '/eggs/list/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    "description" =>"",
                    "name" => $version->project->name,
                    "revision" => "1",
                    "slug" => $version->project->slug,
                    "size_of_content" => $version->project->size_of_content,
                    "size_of_zip" => 0,
                    "category" => $category->slug,
		    "download_counter" => 0,
		    "status" => "unknown"
                ]
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testProjectSearchJson()
    {
        $response = $this->json('GET', '/eggs/search/something/json');
        $response->assertStatus(200)->assertExactJson([]);
        $category = factory(Category::class)->create();

        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();

        $len = strlen($version->project->name);

        $response = $this->json('GET', '/eggs/search/'.substr($version->project->name, 2, $len-4).'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    "description" =>"",
                    "name" => $version->project->name,
                    "revision" => "1",
                    "slug" => $version->project->slug,
                    "size_of_content" => 0,
                    "size_of_zip" => 0,
                    "category" => $category->slug,
		    "download_counter" => 0,
		    "status" => "unknown"
                ]
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testProjectCategoryJson()
    {
        $category = factory(Category::class)->create(['name' => 'Test']);

        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();

        $response = $this->json('GET', '/eggs/category/test/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    "description" =>"",
                    "name" => $version->project->name,
                    "revision" => "1",
                    "slug" => $version->project->slug,
                    "size_of_content" => 0,
                    "size_of_zip" => 0,
                    "category" => $category->slug,
                    "download_counter" => 0,
		    "status" => 'unknown'
                ]
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testCategoriesJson()
    {
        $category = factory(Category::class)->create();

        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();
        $version->zip = 'some_path.tar.gz';
        $version->project->category_id = $category->id;
        $version->project->save();
        $version->save();

        $response = $this->json('GET', '/eggs/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    "name" => $category->name,
                    "slug" => $category->slug,
                    "eggs" => 1
                ]
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testCategoriesCountJson()
    {
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $this->be($user);
        $version = factory(Version::class)->create();
        $version->zip = 'iets anders';
        $version->project->category_id = $category->id;
        $version->project->save();
        $version->save();

        $response = $this->json('GET', '/eggs/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    "name" => $category->name,
                    "slug" => $category->slug,
                    "eggs" => 1
                ]
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testCategoriesUnpublishedJson()
    {
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $this->be($user);
        $version = factory(Version::class)->create();
        $version->project->category_id = $category->id;
        $version->project->save();
        $version->save();

        $response = $this->json('GET', '/eggs/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    "name" => $category->name,
                    "slug" => $category->slug,
                    "eggs" => 0
                ]
            ]);
    }

    /**
     * Check public project view.
     */
    public function testProjectShow()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $version = factory(Version::class)->create();

        $response = $this->get('/projects/'.$version->project->slug.'');
        $response->assertStatus(200)
            ->assertViewHas('project');
    }

    /**
     * Check public file view.
     */
    public function testFileShow()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $file = factory(File::class)->create();

        $response = $this->get('/files/'.$file->id.'');
        $response->assertStatus(200)
            ->assertViewHas('file');
    }
}
