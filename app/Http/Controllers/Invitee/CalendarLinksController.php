<?php

namespace App\Http\Controllers\Invitee;

use App\Http\Requests\IndexCalendarLinksRequest;
use App\Http\Controllers\Controller;
use App\Services\CalendarLinksService;

class CalendarLinksController extends Controller {
    
    private $linksService;

    public function __construct(CalendarLinksService $linksService) {
        $this->linksService = $linksService;
    }
    
    public function index(IndexCalendarLinksRequest $request) {
        return response()->json([
            'data' => $this->linksService->getLinks()
        ]);
    }
}
