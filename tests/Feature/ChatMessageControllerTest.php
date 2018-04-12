<?php

namespace Tests\Feature;

use App\Events\ChatMessage;
use App\User;
use Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatMessageControllerTest extends TestCase
{

  public function setUp()
  {
    parent::setUp();
    $this->withoutExceptionHandling();
  }

  /** @test */
  public function logged_user_can_add_message_to_chat()
  {
    Event::fake();

    $user = factory(User::class)->create();

    $response = $this->actingAs($user)->post('/chat_message', [
      'body' => 'Missatge de prova'
    ]);

    $response->assertSuccessful();

    Event::assertDispatched(ChatMessage::class, function ($e) {
      return $e->message === 'Missatge de prova';
    });
  }
}
