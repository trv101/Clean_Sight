<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Controller method to sort incidents by priority
    public function index(Request $request)
    {
        // Define the priority mapping
        $priorityMap = [
            'Low' => 1,
            'Medium' => 2,
            'High' => 3,
        ];

        // Retrieve incidents and sort by priority
        $incidents = Incident::all();

        // Sort incidents based on the priority using the mapping
        $incidents = $incidents->sortBy(function ($incident) use ($priorityMap) {
            return $priorityMap[$incident->priority] ?? 0; // Default to 0 if priority is not found
        });

        // Optionally, sort in descending order (High priority first)
        if ($request->get('sort_order') === 'desc') {
            $incidents = $incidents->sortByDesc(function ($incident) use ($priorityMap) {
                return $priorityMap[$incident->priority] ?? 0; // Default to 0 if priority is not found
            });
        }

        return view('incidents.index', compact('incidents'));
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
        $incident = Incident::find($id);
        return view("incidents.show", compact("incident"));
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
        {      
            $previousStatus = $incident->status;
            $previousCorrectiveAction = $incident->corrective_action;

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
                'corrective_action' => $request->corrective_action,
                'updated_by_user_id' => Auth::id(), // You can keep status as is or modify it if needed
                'last_edit_details' => 'Status changed from ' . $previousStatus . ' to ' . $request->status . 
                               '. Corrective Action: ' . $previousCorrectiveAction . ' -> ' . $request->corrective_action,
            ]);
          
    
            return redirect()->route('incidents.index')->with('success', 'Incident Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $incident = Incident::find($id);
        $incident->delete();

        return redirect()->route('incidents.index')->with('success', 'Incident Deleted!');
    }
}
