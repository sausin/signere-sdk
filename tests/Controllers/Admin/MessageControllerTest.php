<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Message;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;

class MessageControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }
    
    /** @test */
    public function an_admin_can_details_for_all_messages()
    {
        $message = m::mock(Message::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'all'));

        $documentId = str_random(36);

        $message->shouldReceive('all')
                ->once()
                ->with($documentId)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Message::class, $message);

        $this->actingAs(new Fakes\User)
            ->json('get', '/signere/admin/document/' . $documentId . '/messages')
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_details_for_a_particular_message()
    {
        $message = m::mock(Message::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'get'));

        $documentId = str_random(36);
        $messageId = str_random(36);

        $message->shouldReceive('get')
                ->once()
                ->with($messageId)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Message::class, $message);

        $this->actingAs(new Fakes\User)
            ->json('get', sprintf('/signere/admin/messages/%s', $messageId))
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_new_message()
    {
        $message = m::mock(Message::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'sendMessage'));

        $documentId = str_random(36);
        $signeeRef = str_random(36);

        $body1 = [
            'document_id' => $documentId,
            'message' => 'dkjfslkjfsk',
            'email' => 'hey@stupid.guy',
            'signature' => 'why',
            'signee_ref' => $signeeRef
        ];
        $body2 = [
            'DocumentID' => $documentId,
            'EmailMessage' => 'dkjfslkjfsk',
            'RecipientEmailAddress' => 'hey@stupid.guy',
            'SenderSignature' => 'why',
            'SigneeRef' => $signeeRef
        ];

        $message->shouldReceive('sendMessage')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Message::class, $message);

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/admin/messages', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_send_a_new_message_to_a_signee_with_new_details()
    {
        $message = m::mock(Message::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'sendNewMessage'));

        $documentId = str_random(36);
        $signeeRef = str_random(36);

        $body1 = [
            'document_id' => $documentId,
            'email' => 'hey@stupid.guy',
            'replace_email' => 'true',
            'signee_ref' => $signeeRef
        ];
        $body2 = [
            'DocumentID' => $documentId,
            'RecipientEmailAddress' => 'hey@stupid.guy',
            'ReplaceEmail' => 'true',
            'SigneeRef' => $signeeRef
        ];

        $message->shouldReceive('sendNewMessage')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Message::class, $message);

        $this->actingAs(new Fakes\User)
            ->json('PATCH', '/signere/admin/messages', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_send_a_new_message_to_a_signee()
    {
        $message = m::mock(Message::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'sendNewMessage'));

        $documentId = str_random(36);
        $signeeRef = str_random(36);

        $body1 = [
            'document_id' => $documentId,
            'signee_ref' => $signeeRef
        ];
        $body2 = [
            'DocumentID' => $documentId,
            'SigneeRef' => $signeeRef
        ];

        $message->shouldReceive('sendNewMessage')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Message::class, $message);

        $this->actingAs(new Fakes\User)
            ->json('PATCH', '/signere/admin/messages', $body1)
            ->assertStatus(200);
    }
}
