<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Incident;
use Illuminate\Support\Facades\Log;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidents = Incident::all() ;
        return view("incidents.index", compact("incidents"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("incidents.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        {
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'incident_type' => 'required|in:IT,HR',
                'impact' => 'required|in:Low,Medium,High',
                'urgency' => 'required|in:Low,Medium,High',
                'category' => 'required',
            ]);
    
            // Calculate priority before saving
            $priority = Incident::calculatePriority($request->impact, $request->urgency);
    
            // Create the new incident
            Incident::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'incident_type' => $request->incident_type,
                'impact' => $request->impact,
                'urgency' => $request->urgency,
                'priority' => $priority, // Automatically assigned
                'category' => $request->category,
                'status' => 'Open',
                'assigned_department' => $request->incident_type === 'IT' ? 'IT' : 'HR',
            ]);

            return redirect()->route('incidents.index')->with('success', 'Incident Reported!');
        
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $incident = Incident::find($id);
        return view("incidents.edit", compact("incident"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incident $incident)
    {
        {   Log::info('Incident update:', [
            'status' => $request->status,
            'corrective_action' => $request->corrective_action,
        ]);
        
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'incident_type' => 'required|in:IT,HR',
                'impact' => 'required|in:Low,Medium,High',
                'urgency' => 'required|in:Low,Medium,High',
                'category' => 'required',
                'status' => 'required|in:Open,In Progress,Resolved,On Hold,Escalated',
                'corrective_action' => 'nullable|string',
            ]);
    
           
            $incident->update([
                'title' => $request->title,
                'description' => $request->description,
                'incident_type' => $request->incident_type,
                'impact' => $request->impact,
                'urgency' => $request->urgency,
                'category' => $request->category,
                'priority' => Incident::calculatePriority($request->impact, $request->urgency), // Recalculate priority
                'status' => $request->status,
                'corrective_action' => $request->corrective_action, // You can keep status as is or modify it if needed
            ]);
          
    
            return redirect()->route('incidents.index')->with('success', 'Incident Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
