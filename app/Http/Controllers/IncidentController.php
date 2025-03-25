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
    public function index(Request $request)
{
    // Define the priority mapping
    $priorityMap = [
        'Low' => 1,
        'Medium' => 2,
        'High' => 3,
    ];

    // Get the status filter values from the query parameters (defaults to all if not set)
    $statuses = $request->input('status', []);

    // Start the query for incidents
    $query = Incident::query();

    // Apply the status filter if selected
    if (!empty($statuses)) {
        $query->whereIn('status', $statuses);
    }

    // Apply the sorting by priority
    $sortOrder = $request->get('sort_order');
    if ($sortOrder) {
        // Sort incidents by priority before retrieving from the database
        $query = $query->orderByRaw("FIELD(priority, 'Low', 'Medium', 'High') " . ($sortOrder === 'desc' ? 'DESC' : 'ASC'));
    }

    // Retrieve the incidents based on the filter and sorting
    $incidents = $query->get();

    // Return the view with incidents and selected statuses for the form
    return view('incidents.index', compact('incidents', 'statuses'));
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
