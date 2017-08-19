<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Sausin\Signere\DocumentProvider;
use Illuminate\Support\Facades\Config;
use Sausin\Signere\Http\Controllers\Controller;

class DocumentProviderController extends Controller
{
    /** @var \Sausin\Signere\DocumentProvider */
    protected $dp;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\DocumentProvider $dp
     * @return void
     */
    public function __construct(DocumentProvider $dp)
    {
        parent::__construct();

        $this->dp = $dp;
    }

    /**
     * Retrieves a document provider account.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->dp->getProviderAccount(Config::get('signere.id'))
                ->getBody()
                ->getContents();
    }

    /**
     * Retrieves usage for this account.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return $this->dp->getUsage(Config::get('signere.id'))
                ->getBody()
                ->getContents();
    }

    /**
     * Creates a new document provider account
     * for submitting documents.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'address1' => 'bail|required|string|min:1|max:255',
            'address2' => 'bail|nullable|string|min:1|max:255',
            'city' => 'bail|required|string|min:1|max:255',
            'country' => 'bail|nullable|string|min:1|max:255',
            'billing_plan' => 'bail|nullable|string|in:Small,Medium,Large',
            'postcode' => [
                'bail',
                'required',
                'regex:/^\d{4}$/i',
            ],
            'company_email' => 'bail|required|email|max:255',
            'company_phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\+47\d{8}$/i',
            ],
            'dealer_id' => 'bail|required|string|size:36',
            'legal_contact_email' => 'bail|required|email|max:255',
            'legal_contact_name' => 'bail|required|string|min:1|max:255',
            'legal_contact_phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\+47\d{8}$/i',
            ],
            'mva_number' => 'bail|required|numeric|min:800000000|max:999999999',
            'name' => 'bail|required|string|min:1|max:255',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'address1' => 'BillingAddress1',
            'address2' => 'BillingAddress2',
            'city' => 'BillingCity',
            'country' => 'BillingCountry',
            'billing_plan' => 'BillingPlan',
            'postcode' => 'BillingPostalCode',
            'company_email' => 'CompanyEmail',
            'company_phone' => 'CompanyPhone',
            'dealer_id' => 'DealerId',
            'legal_contact_email' => 'LegalContactEmail',
            'legal_contact_name' => 'LegalContactName',
            'legal_contact_phone' => 'LegalContactPhone',
            'mva_number' => 'MvaNumber',
            'name' => 'Name',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->dp->create($body)
                ->getBody()
                ->getContents();
    }

    /**
     * Updates the information stored in a given
     * document provider account.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'address1' => 'bail|sometimes|string|min:1|max:255',
            'address2' => 'bail|sometimes|nullable|string|min:1|max:255',
            'city' => 'bail|sometimes|string|min:1|max:255',
            'country' => 'bail|sometimes|nullable|string|min:1|max:255',
            'billing_plan' => 'bail|sometimes|nullable|string|in:Small,Medium,Large',
            'postcode' => [
                'bail',
                'sometimes',
                'regex:/^\d{4}$/i',
            ],
            'company_email' => 'bail|sometimes|email|max:255',
            'company_phone' => [
                'bail',
                'sometimes',
                'string',
                'regex:/^\+47\d{8}$/i',
            ],
            'dealer_id' => 'bail|sometimes|string|size:36',
            'legal_contact_email' => 'bail|sometimes|email|max:255',
            'legal_contact_name' => 'bail|sometimes|string|min:1|max:255',
            'legal_contact_phone' => [
                'bail',
                'sometimes',
                'string',
                'regex:/^\+47\d{8}$/i',
            ],
            'mva_number' => 'bail|sometimes|numeric|min:800000000|max:999999999',
            'name' => 'bail|sometimes|string|min:1|max:255',
        ]);

        // this is used to only set the keys which have been sent in
        $useKeys = [
            'address1' => 'BillingAddress1',
            'address2' => 'BillingAddress2',
            'city' => 'BillingCity',
            'country' => 'BillingCountry',
            'billing_plan' => 'BillingPlan',
            'postcode' => 'BillingPostalCode',
            'company_email' => 'CompanyEmail',
            'company_phone' => 'CompanyPhone',
            'dealer_id' => 'DealerId',
            'legal_contact_email' => 'LegalContactEmail',
            'legal_contact_name' => 'LegalContactName',
            'legal_contact_phone' => 'LegalContactPhone',
            'mva_number' => 'MvaNumber',
            'name' => 'Name',
        ];

        // check which keys are available in the request
        $available = array_intersect(array_keys($useKeys), array_keys($request->all()));

        $body = [];

        // set the body up
        foreach ($available as $use) {
            $body[$useKeys[$use]] = $request->$use;
        }

        return $this->dp->update($body)
                ->getBody()
                ->getContents();
    }
}
