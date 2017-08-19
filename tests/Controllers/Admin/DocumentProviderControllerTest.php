<?php

namespace Sausin\Signere\Tests\Controllers\Admin;

use Mockery as m;
use GuzzleHttp\Psr7\Response;
use Sausin\Signere\DocumentProvider;
use Sausin\Signere\Tests\Controllers\Fakes\User;
use Sausin\Signere\Tests\Controllers\AbstractControllerTest;

class DocumentProviderControllerTest extends AbstractControllerTest
{
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function an_admin_can_get_account_details()
    {
        $dp = m::mock(DocumentProvider::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dp, 'getProviderAccount'));

        $dp->shouldReceive('getProviderAccount')
                ->once()
                ->with('id')
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentProvider::class, $dp);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/provider')
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_get_usage_details()
    {
        $dp = m::mock(DocumentProvider::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dp, 'getUsage'));

        $dp->shouldReceive('getUsage')
                ->once()
                ->with('id')
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentProvider::class, $dp);

        $this->actingAs(new User)
            ->json('GET', '/signere/admin/provider/usage')
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_provider()
    {
        $dp = m::mock(DocumentProvider::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dp, 'create'));

        $body1 = [
            'address1' => $address1 = str_random(10),
            'address2' => $address2 = str_random(10),
            'city' => $city = str_random(10),
            'country' => $country = str_random(10),
            'billing_plan' => $billing_plan = 'Small',
            'postcode' => $postcode = rand(1000, 9999),
            'company_email' => $company_email = 'u@two.com',
            'company_phone' => $company_phone = '+4712345678',
            'dealer_id' => $dealer_id = str_random(36),
            'legal_contact_email' => $legal_contact_email = 'post@rand.ax',
            'legal_contact_name' => $legal_contact_name = str_random(10),
            'legal_contact_phone' => $legal_contact_phone = '+4712345678',
            'mva_number' => $mva_number = rand(800000000, 999999999),
            'name' => $name = str_random(10),
        ];

        $body2 = [
            'BillingAddress1' => $address1,
            'BillingAddress2' => $address2,
            'BillingCity' => $city,
            'BillingCountry' => $country,
            'BillingPlan' => $billing_plan,
            'BillingPostalCode' => $postcode,
            'CompanyEmail' => $company_email,
            'CompanyPhone' => $company_phone,
            'DealerId' => $dealer_id,
            'LegalContactEmail' => $legal_contact_email,
            'LegalContactName' => $legal_contact_name,
            'LegalContactPhone' => $legal_contact_phone,
            'MvaNumber' => $mva_number,
            'Name' => $name,
        ];

        $dp->shouldReceive('create')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentProvider::class, $dp);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/provider', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_create_a_provider_with_only_required_details()
    {
        $dp = m::mock(DocumentProvider::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dp, 'create'));

        $body1 = [
            'address1' => $address1 = str_random(10),
            'city' => $city = str_random(10),
            'postcode' => $postcode = rand(1000, 9999),
            'company_email' => $company_email = 'u@two.com',
            'company_phone' => $company_phone = '+4712345678',
            'dealer_id' => $dealer_id = str_random(36),
            'legal_contact_email' => $legal_contact_email = 'post@rand.ax',
            'legal_contact_name' => $legal_contact_name = str_random(10),
            'legal_contact_phone' => $legal_contact_phone = '+4712345678',
            'mva_number' => $mva_number = rand(800000000, 999999999),
            'name' => $name = str_random(10),
        ];

        $body2 = [
            'BillingAddress1' => $address1,
            'BillingCity' => $city,
            'BillingPostalCode' => $postcode,
            'CompanyEmail' => $company_email,
            'CompanyPhone' => $company_phone,
            'DealerId' => $dealer_id,
            'LegalContactEmail' => $legal_contact_email,
            'LegalContactName' => $legal_contact_name,
            'LegalContactPhone' => $legal_contact_phone,
            'MvaNumber' => $mva_number,
            'Name' => $name,
        ];

        $dp->shouldReceive('create')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentProvider::class, $dp);

        $this->actingAs(new User)
            ->json('POST', '/signere/admin/provider', $body1)
            ->assertStatus(200);
    }

    /** @test */
    public function an_admin_can_update_a_provider_with_only_some_details()
    {
        $dp = m::mock(DocumentProvider::class);

        // make a check on the object if the method actually exists
        // this is to be certain that code changes in the original
        // class will not lead to this test passing by mistake
        $this->assertTrue(method_exists($dp, 'update'));

        $body1 = [
            'address1' => $address1 = str_random(10),
            'city' => $city = str_random(10),
            'legal_contact_email' => $legal_contact_email = 'post@rand.ax',
            'legal_contact_phone' => $legal_contact_phone = '+4712345678',
            'name' => $name = str_random(10),
        ];

        $body2 = [
            'BillingAddress1' => $address1,
            'BillingCity' => $city,
            'LegalContactEmail' => $legal_contact_email,
            'LegalContactPhone' => $legal_contact_phone,
            'Name' => $name,
        ];

        $dp->shouldReceive('update')
                ->once()
                ->with($body2)
                ->andReturn(new Response(200, [], ''));

        $this->app->instance(DocumentProvider::class, $dp);

        $this->actingAs(new User)
            ->json('PATCH', '/signere/admin/provider', $body1)
            ->assertStatus(200);
    }
}
