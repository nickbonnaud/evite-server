<?php

namespace App\Http\Controllers\Invitee;

use App\Http\Requests\IndexRsvpRequest;
use App\Http\Resources\InviteeRsvpResource;
use App\Services\InviteeRsvpService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRsvpRequest;
use App\Models\Rsvp;

class RsvpController extends Controller {

    private $rsvpService;

    public function __construct(InviteeRsvpService $rsvpService) {
        $this->rsvpService = $rsvpService;
    }
    
    public function index(IndexRsvpRequest $request) {
        $rsvp = $this->rsvpService->getRsvp($request->id);
        return new InviteeRsvpResource($rsvp);
    }

    public function update(Rsvp $rsvp, UpdateRsvpRequest $request) {
        $this->rsvpService->updateRsvp($rsvp, $request->validated());
        return new InviteeRsvpResource($rsvp->fresh());
    }
}
