<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;

class EventController extends Controller {

    public function updateParticipantStatus(Request $request) {
        $participant = Participant::find($request->participant_id);
        if (!$participant) {
            return response()->json(['error' => 'Participant not found'], 404);
        }

        // Update status
        $participant->status = $request->status; // "pending", "approved", "rejected"
        $participant->save();

        return response()->json(['success' => 'Status updated successfully']);
    }

    public function markCompletion(Request $request) {
        $participant = Participant::find($request->participant_id);
        if (!$participant) {
            return response()->json(['error' => 'Participant not found'], 404);
        }

        // Update completion status
        $participant->is_completed = $request->completed; // true or false
        $participant->save();

        return response()->json(['success' => 'Completion status updated']);
    }

    public function getParticipantsReport(Request $request) {
        $query = Participant::where('event_id', $request->event_id);

        // Apply filters
        if ($request->filter == 'completed') {
            $query->where('is_completed', true);
        } elseif ($request->filter == 'not_completed') {
            $query->where('is_completed', false);
        }

        $participants = $query->get();

        return response()->json($participants);
    }
}