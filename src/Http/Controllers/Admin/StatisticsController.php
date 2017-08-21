<?php

namespace Sausin\Signere\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Sausin\Signere\Statistics;
use Sausin\Signere\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    /** @var \Sausin\Signere\Statistics */
    protected $statistics;

    /**
     * Create a new controller instance.
     *
     * @param  \Sausin\Signere\Statistics $statistics
     */
    public function __construct(Statistics $statistics)
    {
        parent::__construct();

        $this->statistics = $statistics;
    }

    /**
     * Get the statistics contrained by the given parameters.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'year' => 'sometimes|numeric|nullable|min:2015|max:'.Carbon::now()->year,
            'month' => 'sometimes|numeric|nullable|min:1|max:12',
            'day' => 'sometimes|numeric|nullable|min:1|max:31',
        ]);

        $allowedStatus = ['All', 'Canceled', 'Signed', 'Expired', 'Unsigned', 'Changed', 'PartialSigned'];
        $status = in_array($request->status, $allowedStatus, true) ? $request->status : 'All';

        return $this->statistics->get($request->year, $request->month, $request->day, $status)
                ->getBody()
                ->getContents();
    }
}
