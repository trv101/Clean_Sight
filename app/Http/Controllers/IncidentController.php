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
     
         // Get the sort parameters (if any)
         $sortBy = $request->input('sort_by', 'created_at'); // Default sorting by created_at
         $sortOrder = $request->input('sort_order', 'asc'); // Default sorting in ascending order
     
         // Apply sorting logic
         if ($request->has('sort_by') && $request->has('sort_order')) {
             // If sorting by 'status', apply custom order using FIELD() in SQL
             if ($sortBy === 'status') {
                 $query->orderByRaw("FIELD(status, 'Open', 'In Progress', 'Resolved', 'On Hold', 'Escalated') $sortOrder");
             } elseif ($sortBy === 'priority') {
                 // Manually order priorities: Low, Medium, High
                 $query->orderByRaw("FIELD(priority, 'Low', 'Medium', 'High') $sortOrder");
             } else {
                 // For other columns, apply default sorting
                 $query->orderBy($sortBy, $sortOrder);
             }
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
    // Get 'from_dashboard' value from query string (if exists)
    $from_dashboard = $request->query('from_dashboard');
    
    // Default the assigned department to an empty string
    $assignedDepartment = '';

    // Set the assigned department based on incident type
    switch ($request->incident_type) {
        case 'Electrical maintenance':
            $assignedDepartment = 'Electrical';
            break;
        case 'Plumbing maintenance':
            $assignedDepartment = 'Plumbing';
            break;
        case 'Garden Maintenance':
            $assignedDepartment = 'Gardening';
            break;
        case 'Cleaning Maintenance':
            $assignedDepartment = 'Cleaning';
            break;
        case 'Other':
            $assignedDepartment = 'General';
            break;
    }

    // Validate incoming request data (excluding assigned_department)
    $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'incident_type' => 'required|in:Electrical maintenance,Plumbing maintenance,Garden Maintenance,Cleaning Maintenance,Other',
        'impact' => 'required|in:Low,Medium,High',
        'urgency' => 'required|in:Low,Medium,High',
        'category' => 'required',
        'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Image validation (optional)
    ]);

    // Calculate priority based on impact and urgency
    $priority = Incident::calculatePriority($request->impact, $request->urgency);

    // Prepare data for new incident
    $incidentData = $request->only(['title', 'description', 'incident_type', 'impact', 'urgency', 'category']);

    // Add the assigned department to the incident data
    $incidentData['assigned_department'] = $assignedDepartment;

    // Handle image upload if present
    $path = '';
    $filename = '';
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $path = 'uploads/incident/';
        $file->move(public_path($path), $filename);
    }

    // Create the new incident
    Incident::create([
        'user_id' => Auth::id(),
        'priority' => $priority,
        'status' => 'Open',
        'photo' => $path . $filename, // Store the photo path if it exists
        ...$incidentData, // Merge the incident data with the assigned department
    ]);

    // Redirect based on whether the request came from the dashboard or not
    if ($from_dashboard == 'true') {
        return redirect()->route('dashboard')->with('success', 'Incident Reported!');
    }

    return redirect()->route('incidents.index')->with('success', 'Incident Reported!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the incident by its ID
        $incident = Incident::find($id);

        // Check if incident exists
        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident not found.');
        }

        return view("incidents.show", compact("incident"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the incident by its ID for editing
        $incident = Incident::find($id);

        // Check if incident exists
        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident not found.');
        }

        return view("incidents.edit", compact("incident"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incident $incident)
    {      
        // Store previous values for comparison in last_edit_details
        $previousStatus = $incident->status;
        $previousCorrectiveAction = $incident->corrective_action;

        // Validate incoming request data for editing
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'incident_type' => 'required|in:Electrical maintenance,Plumbing maintenance,Garden Maintenance,Cleaning Maintenance,Other',
            'impact' => 'required|in:Low,Medium,High',
            'urgency' => 'required|in:Low,Medium,High',
            'category' => 'required',
            'status' => 'required|in:Open,In Progress,Resolved,On Hold,Escalated',
            'corrective_action' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Image validation (optional)
        ]);

        // Handle image upload for the edit
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($incident->photo && file_exists(public_path($incident->photo))) {
                unlink(public_path($incident->photo));
            }

            // Store the new photo
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $path = 'uploads/incident/';
            $file->move(public_path($path), $filename);

            // Save the new photo path
            $incident->photo = $path . $filename;
        }

        // Update the incident with new data
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
        // Find the incident and delete its photo if it exists
        $incident = Incident::find($id);

        if ($incident) {
            // Check if photo exists and delete it
            if ($incident->photo && file_exists(public_path($incident->photo))) {
                // File::delete($incident->photo);
                unlink(public_path($incident->photo));
            }

            // Delete the incident record from the database
            $incident->delete();
        }

        return redirect()->route('incidents.index')->with('success', 'Incident Deleted!');
    }

    /**
     * Display the dashboard for incidents created by the logged-in user.
     */
    public function dashboard()
    {
        // Get the incidents created by the logged-in user
        $incidents = Incident::where('user_id', auth()->id())->get();

        // Return the dashboard view with the incidents
        return view('dashboard', compact('incidents'));
    }
}
