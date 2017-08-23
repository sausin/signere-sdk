<?php

namespace Sausin\Signere\Tests;

use Sausin\Signere\Form;
use Sausin\Signere\ApiKey;
use Sausin\Signere\Events;
use Sausin\Signere\Status;
use Sausin\Signere\Invoice;
use Sausin\Signere\Message;
use Sausin\Signere\Document;
use Sausin\Signere\Receiver;
use Sausin\Signere\RequestId;
use Sausin\Signere\Statistics;
use Sausin\Signere\DocumentJob;
use Sausin\Signere\DocumentFile;
use Sausin\Signere\ExternalSign;
use Sausin\Signere\ExternalLogin;
use Sausin\Signere\DocumentConvert;
use Sausin\Signere\DocumentProvider;

class EnvironmentTest extends IntegrationTest
{
    /** @test */
    public function apikey_should_give_test_url_in_test_environment()
    {
        $apikey = $this->app->make(ApiKey::class);

        $this->assertEquals('https://testapi.signere.no/api/ApiToken', $apikey->getBaseUrl());
    }

    /** @test */
    public function document_should_give_test_url_in_test_environment()
    {
        $document = $this->app->make(Document::class);

        $this->assertEquals('https://testapi.signere.no/api/Document', $document->getBaseUrl());
    }

    /** @test */
    public function doc_convert_should_give_test_url_in_test_environment()
    {
        $docConvert = $this->app->make(DocumentConvert::class);

        $this->assertEquals('https://testapi.signere.no/api/DocumentConvert', $docConvert->getBaseUrl());
    }

    /** @test */
    public function doc_file_should_give_test_url_in_test_environment()
    {
        $docFile = $this->app->make(DocumentFile::class);

        $this->assertEquals('https://testapi.signere.no/api/DocumentFile', $docFile->getBaseUrl());
    }

    /** @test */
    public function doc_job_should_give_test_url_in_test_environment()
    {
        $docJob = $this->app->make(DocumentJob::class);

        $this->assertEquals('https://testapi.signere.no/api/DocumentJob', $docJob->getBaseUrl());
    }

    /** @test */
    public function doc_provider_should_give_test_url_in_test_environment()
    {
        $docProvider = $this->app->make(DocumentProvider::class);

        $this->assertEquals('https://testapi.signere.no/api/DocumentProvider', $docProvider->getBaseUrl());
    }

    /** @test */
    public function events_should_give_test_url_in_test_environment()
    {
        $events = $this->app->make(Events::class);

        $this->assertEquals('https://testapi.signere.no/api/events/encryptionkey', $events->getBaseUrl());
    }

    /** @test */
    public function extern_loging_should_give_test_url_in_test_environment()
    {
        $exLogin = $this->app->make(ExternalLogin::class);

        $this->assertEquals('https://testapi.signere.no/api/ExternalLogin', $exLogin->getBaseUrl());
    }

    /** @test */
    public function extern_sign_should_give_test_url_in_test_environment()
    {
        $extSign = $this->app->make(ExternalSign::class);

        $this->assertEquals('https://testapi.signere.no/api/externalsign', $extSign->getBaseUrl());
    }

    /** @test */
    public function form_should_give_test_url_in_test_environment()
    {
        $form = $this->app->make(Form::class);

        $this->assertEquals('https://testapi.signere.no/api/Form', $form->getBaseUrl());
    }

    /** @test */
    public function invoice_should_give_test_url_in_test_environment()
    {
        $invoice = $this->app->make(Invoice::class);

        $this->assertEquals('https://testapi.signere.no/api/Invoice', $invoice->getBaseUrl());
    }

    /** @test */
    public function message_should_give_test_url_in_test_environment()
    {
        $message = $this->app->make(Message::class);

        $this->assertEquals('https://testapi.signere.no/api/Message', $message->getBaseUrl());
    }

    /** @test */
    public function receiver_should_give_test_url_in_test_environment()
    {
        $receiver = $this->app->make(Receiver::class);

        $this->assertEquals('https://testapi.signere.no/api/Receiver', $receiver->getBaseUrl());
    }

    /** @test */
    public function request_id_should_give_test_url_in_test_environment()
    {
        $reqId = $this->app->make(RequestId::class);

        $this->assertEquals('https://testapi.signere.no/api/SignereId', $reqId->getBaseUrl());
    }

    /** @test */
    public function stats_should_give_test_url_in_test_environment()
    {
        $stats = $this->app->make(Statistics::class);

        $this->assertEquals('https://testapi.signere.no/api/Statistics', $stats->getBaseUrl());
    }

    /** @test */
    public function status_should_give_test_url_in_test_environment()
    {
        $status = $this->app->make(Status::class);

        $this->assertEquals('https://testapi.signere.no/api/Status', $status->getBaseUrl());
    }
}
