<?php

namespace App\Http\Controllers\Inviter;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRsvpsRequest;
use App\Http\Resources\InviterRsvpResource;
use App\Services\InviterRsvpService;
use Illuminate\Http\Request;

class RsvpController extends Controller {

    private $rsvpService;

    public function __construct(InviterRsvpService $rsvpService) {
        $this->rsvpService = $rsvpService;
    }
    
    public function index(Request $request) {
        return InviterRsvpResource::collection($this->rsvpService->getRsvps());
    }

    public function post(StoreRsvpsRequest $request) {
        $this->rsvpService->createRsvps($request->user(), $request->validated());
        return InviterRsvpResource::collection($this->rsvpService->getRsvps());
    }
}
