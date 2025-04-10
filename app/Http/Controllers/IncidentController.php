<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the status filter values from the query parameters (defaults to all if not set)
        $statuses = $request->input('status', []);

        // Start the query for incidents
        $query = Incident::query();

        // Apply the status filter if selected
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Retrieve incidents, eager load the updatedByUser relationship
        $incidents = $query->with('updatedByUser')->get();

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
        $from_dashboard = $request->query('from_dashboard');
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'incident_type' => 'required|in:Equipment Issues,Safety Hazards,Workplace Injuries,...',
            'impact' => 'required|in:Low,Medium,High',
            'urgency' => 'required|in:Low,Medium,High',
            'category' => 'required',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Image validation
        ]);
    
        // Calculate priority before saving
        $priority = Incident::calculatePriority($request->impact, $request->urgency);
    
        // Prepare data for new incident
        $incidentData = $request->only(['title', 'description', 'incident_type', 'impact', 'urgency', 'category', 'assigned_department']);
    
        // Handle image upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('incident_photos', 'public');
            $incidentData['photo'] = $photoPath;
        }

        // Create the new incident
        Incident::create([
            'user_id' => Auth::id(),
            'priority' => $priority, 
            'status' => 'Open',
            'photo' => $incidentData['photo'] ?? null, // Store photo path if it exists
            ...$incidentData,
        ]);
            
        if ($from_dashboard == 'true') {
            // If from_dashboard is true, redirect to the dashboard
            return redirect()->route('dashboard')->with('success', 'Incident Reported!');
        }
        
        // If from_dashboard is not true, redirect to the incidents index page
        return redirect()->route('incidents.index')->with('success', 'Incident Reported!');
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
        $previousStatus = $incident->status;
        $previousCorrectiveAction = $incident->corrective_action;

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'incident_type' => 'required|in:Equipment Issues,Safety Hazards,Workplace Injuries,...',
            'impact' => 'required|in:Low,Medium,High',
            'urgency' => 'required|in:Low,Medium,High',
            'category' => 'required',
            'status' => 'required|in:Open,In Progress,Resolved,On Hold,Escalated',
            'corrective_action' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Image validation
        ]);

        // Handle image upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($incident->photo && file_exists(storage_path('app/public/'.$incident->photo))) {
                unlink(storage_path('app/public/'.$incident->photo));
            }

            $photoPath = $request->file('photo')->store('incident_photos', 'public');
            $incident->photo = $photoPath;
        }

        // Update incident with new data
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
            'updated_by_user_id' => Auth::id(),
            'last_edit_details' => '<strong>Status changed from ' . $previousStatus . ' to ' . $request->status . '.</strong><br>' .
                                   '<strong>Corrective Action: ' . $previousCorrectiveAction . ' -> ' . $request->corrective_action . '</strong>',
        ]);

        return redirect()->route('incidents.index')->with('success', 'Incident Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $incident = Incident::find($id);
        // Delete the photo if exists
        if ($incident->photo && file_exists(storage_path('app/public/'.$incident->photo))) {
            unlink(storage_path('app/public/'.$incident->photo));
        }
        $incident->delete();

        return redirect()->route('incidents.index')->with('success', 'Incident Deleted!');
    }

    public function dashboard()
    {
        // Get the incidents created by the logged-in user
        $incidents = Incident::where('user_id', auth()->id())->get();

        // Return the dashboard view with the incidents
        return view('dashboard', compact('incidents'));
    }
}
