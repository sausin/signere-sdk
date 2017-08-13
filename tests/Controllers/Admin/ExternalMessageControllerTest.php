<?php

namespace Sausin\Signere\Tests\Controllers;

use Mockery as m;
use GuzzleHttp\Client;
use Sausin\Signere\Message;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;

class ExternalMessageControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();
        
        m::close();
    }
    
    /** @test */
    public function an_admin_can_create_a_new_message_for_external_person()
    {
        $message = m::mock(Message::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($message, 'sendExternalMessage'));

        $documentId = str_random(36);
        $signeeRef = str_random(36);

        $body1 = [
            'document_id' => $documentId,
            'message' => 'dkjfslkjfsk',
            'email' => 'hey@stupid.guy',
            'phone_number' => '+4712345678',
            'signature' => 'why',
        ];
        $body2 = [
            'DocumentID' => $documentId,
            'EmailMessage' => 'dkjfslkjfsk',
            'RecipientEmailAddress' => 'hey@stupid.guy',
            'MobileNumber' => '+4712345678',
            'SenderSignature' => 'why',
        ];

        $message->shouldReceive('sendExternalMessage')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(Message::class, $message);

        $this->actingAs(new Fakes\User)
            ->json('POST', '/signere/admin/externalMessage', $body1)
            ->assertStatus(200);
    }
}
