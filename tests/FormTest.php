<?php

namespace Sausin\Signere\Tests;

use Mockery as m;
use Carbon\Carbon;
use Sausin\Signere\Form;
use Sausin\Signere\Headers;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Config;

class FormTest extends TestCase
{
    use MakeClient;

    public function setUp()
    {
        $this->headers = m::mock(Headers::class);
        $this->uri = 'https://api.signere.no/api/Form';
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_get_all_forms_for_a_document_provider()
    {
        $detail = '[{"Id":"8210132929654b8eb02bd7e33250c069","Name":"Sign up for new account","Description":"Form to create a new account at Bank international","PublicUrl":"https://skjema.signere.no/start/82101329-2965-4b8e-b02b-d7e33250c069","Incative":true},{"Id":"8210132929654b8eb02bd7e33250c069","Name":"Sign up for new account","Description":"Form to create a new account at Bank international","PublicUrl":"https://skjema.signere.no/start/82101329-2965-4b8e-b02b-d7e33250c069","Incative":true}]';

        $url = sprintf('%s/GetForms', $this->uri);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->get();

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_all_signed_forms_for_a_document_provider()
    {
        $detail = '[{"Id":"8210132929654b8eb02bd7e33250c069","DocumentId":"7230132929654b8eb02bd7e33250c069","Name":"Sign up for new account","Filename":"contract.pdf"},{"Id":"8210132929654b8eb02bd7e33250c069","DocumentId":"7230132929654b8eb02bd7e33250c069","Name":"Sign up for new account","Filename":"contract.pdf"}]';

        $formId = str_random(10);
        $from = Carbon::now()->subDays(5);
        $to = Carbon::now()->subDays(1);
        $url = sprintf(
            '%s/GetSignedForms?formId=%s&fromDate=%s&toDate=%s',
            $this->uri,
            $formId,
            $from->toDateString(),
            $to->toDateString()
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->getAllSigned($formId, $from, $to);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_form_attachments()
    {
        $detail = '';

        $formId = str_random(10);
        $formSignId = str_random(10);
        $attachReference = str_random(10);
        $url = sprintf(
            '%s/GetFormAttachment?formId=%s&FormSignatureId=%s&AttatchmentReference=%s',
            $this->uri,
            $formId,
            $formSignId,
            $attachReference
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->getAttachments($formId, $formSignId, $attachReference);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_signed_form_by_documentId()
    {
        $detail = '{"Id":"8210132929654b8eb02bd7e33250c069","DocumentId":"7230132929654b8eb02bd7e33250c069","Name":"Sign up for new account","Filename":"contract.pdf"}';

        $documentId = str_random(10);
        $url = sprintf('%s/GetSignedForm?documentid=%s', $this->uri, $documentId);

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->getSignedByDocId($documentId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_get_signed_form_by_sessionId()
    {
        $detail = '{"Id":"8210132929654b8eb02bd7e33250c069","DocumentId":"7230132929654b8eb02bd7e33250c069","Name":"Sign up for new account","Filename":"contract.pdf"}';

        $formId = str_random(10);
        $formSessionId = str_random(10);
        $url = sprintf(
            '%s/GetSignedFormByFormSessionId?formId=%s&formSessionId=%s',
            $this->uri,
            $formId,
            $formSessionId
        );

        $this->headers->shouldReceive('make')->once()->withArgs(['GET', $url])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->getSignedBySessionId($formId, $formSessionId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_enable_a_form()
    {
        $detail = '';

        $formId = str_random(10);
        $url = sprintf('%s/EnableForm?formId=%s', $this->uri, $formId);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, []])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->enable($formId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }

    /** @test */
    public function it_can_disable_a_form()
    {
        $detail = '';

        $formId = str_random(10);
        $url = sprintf('%s/DisableForm?formId=%s', $this->uri, $formId);

        $this->headers->shouldReceive('make')->once()->withArgs(['PUT', $url, []])->andReturn([]);

        $form = new Form($this->makeClient($detail), $this->headers);
        $response = $form->disable($formId);

        $this->assertEquals($detail, $response->getBody()->getContents());
    }
}
