<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
		public function testHomePageIsWorkingCorrectly()
		{
				// Act
				$response = $this->get('/');

				// Assert
				$response->assertStatus(200);
				$response->assertSeeText('Home Page');
		}

		public function testContactPageIsWorkingCorrectly()
		{
				// Act
				$response = $this->get('/contact');

				// Assert
				$response->assertStatus(200);
				$response->assertSeeText('Contact Page');
		}
}
