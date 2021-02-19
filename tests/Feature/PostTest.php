<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
		use RefreshDatabase;
		
		public function testNoBlogPostsWhenNothingInDB()
		{
				// ACT
				$response = $this->get('/posts');

				// ASSERT
				$response->assertSeeText('No posts found!');
		}

		public function testSeeOneBlogPostWhenThereIsOneWithoutComments()
		{
				// ARRANGE
				$post = $this->createDummyBlogPost();

				// ACT
				$response = $this->get('/posts');

				// ASSERT
				$response->assertSeeText($post->title);
				$response->assertSeeText('No comments yet');

				$this->assertDatabaseHas('blog_posts', [
					'id' => $post->id,
				]);

		}

		public function testSeeOneBlogPostWithComments()
		{
				// ARRANGE
				$post = $this->createDummyBlogPost();
				Comment::factory(4)->create([
					'blog_post_id' => $post->id
				]);

				// ACT
				$response = $this->get('/posts');

				// ASSERT
				$response->assertSeeText('4 comments');
		}

		public function testStoreValid()
		{
				// ARRANGE
				$params = [
					'title' => 'This is the title',
					'content' => 'This is atleast 10 characters.'
				];

				// ACTION - ASSERT
				$this->post('/posts', $params)
					->assertStatus(302)
					->assertSessionHas('status');

				$this->assertEquals(session('status'), 'The blog post was created!');

		}

		public function testStoreFail()
		{
				// ARRANGE
				$params = [
					'title' => 'X',
					'content' => 'X'
				];

				// ACTION - ASSERT
				$this->post('/posts', $params)
					->assertStatus(302)
					->assertSessionHas('errors');

				$messages = session('errors')->getMessages();

				// dd($messages);

				$this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
				$this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');

		}


		public function testUpdateOfPost()
		{
				// ARRANGE
				$post = $this->createDummyBlogPost();

				// ASSERT
				$this->assertDatabaseHas('blog_posts', [
					'id' => $post->id,
					'title' => $post->title,
					'content' => $post->content,
				]);

				$params = [
					'title' => 'New Title 2',
					'content' => 'This is the content 2'
				];

				// ACTION - ASSERT
				$this->put("/posts/{$post->id}", $params)
					->assertStatus(302)
					->assertSessionHas('status');

				$this->assertEquals(session('status'), 'Post successfully updated!');

				$this->assertDatabaseMissing('blog_posts', [
					'title' => $post->title,
					'content' => $post->content,
				]);

				$this->assertDatabaseHas('blog_posts', [
					'title' => $params['title'],
					'content' => $params['content'],
				]);

		}

		public function testDeletePost(){
			
			// ARRANGE
			$post = $this->createDummyBlogPost();

			$this->delete("/posts/{$post->id}")
				->assertStatus(302)
				->assertSessionHas('status');

			$this->assertEquals(session('status'), 'Post successfully deleted!');

			$this->assertDatabaseMissing('blog_posts', [
				'id' => $post->id,
			]);

		}

		private function createDummyBlogPost(): BlogPost {
			// $post = new BlogPost();
			// $post->title = 'New Title';
			// $post->content = 'This is the content';
			// $post->save();

			return BlogPost::factory()->newTitle()->create();

			// return $post;
		}
}
