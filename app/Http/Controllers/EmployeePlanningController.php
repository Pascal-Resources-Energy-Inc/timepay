<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Planning;
use App\Employee;
use App\ScheduleData;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PlanningImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EmployeePlanningController extends Controller
{
    public function index(Request $request)
    {
        $header = 'forms';
        
        $from = $request->get('from');
        $to = $request->get('to');
        $status = $request->get('status');
        
        $query = Planning::with(['employee', 'approver_info']);
        
        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $plannings = $query->orderBy('date', 'desc')->get();
        
        $get_approvers = new EmployeeApproverController;
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        
        return view('forms.planning.planning', compact('header', 'plannings', 'all_approvers'));
    }
    
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload a valid Excel file (xlsx, xls, csv)'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $import = new PlanningImport();
            Excel::import($import, $request->file('excel_file'));
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Planning data imported successfully!',
                'imported' => $import->getRowCount()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function uploadFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'planning_id' => 'required|exists:plannings,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $planning = Planning::findOrFail($request->planning_id);
            
            if ($request->hasFile('image')) {
                if ($planning->image && Storage::disk('public')->exists($planning->image)) {
                    Storage::disk('public')->delete($planning->image);
                }
                
                $image = $request->file('image');
                $imageName = 'planning_' . $planning->id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('planning_images', $imageName, 'public');
                $planning->image = $imagePath;
            }
            
            if ($request->hasFile('documents')) {
                $documentPaths = [];
                
                if ($planning->documents) {
                    $documentPaths = json_decode($planning->documents, true) ?? [];
                }
                
                foreach ($request->file('documents') as $document) {
                    $documentName = 'planning_' . $planning->id . '_' . time() . '_' . uniqid() . '.' . $document->getClientOriginalExtension();
                    $documentPath = $document->storeAs('planning_documents', $documentName, 'public');
                    
                    $documentPaths[] = [
                        'name' => $document->getClientOriginalName(),
                        'path' => $documentPath,
                        'uploaded_at' => now()->format('Y-m-d H:i:s')
                    ];
                }
                
                $planning->documents = json_encode($documentPaths);
            }
            
            $planning->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading files: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getFiles($id)
    {
        try {
            $planning = Planning::findOrFail($id);
            
            $response = [
                'success' => true,
                'image' => null,
                'documents' => []
            ];
            
            if ($planning->image && Storage::disk('public')->exists($planning->image)) {
                $response['image'] = Storage::url($planning->image);
            }
            
            if ($planning->documents) {
                $documents = json_decode($planning->documents, true);
                
                foreach ($documents as $doc) {
                    if (Storage::disk('public')->exists($doc['path'])) {
                        $response['documents'][] = [
                            'name' => $doc['name'],
                            'url' => Storage::url($doc['path']),
                            'uploaded_at' => $doc['uploaded_at'] ?? null
                        ];
                    }
                }
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching files: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function disablePlanning($id)
    {
        try {
            $planning = Planning::findOrFail($id);
            $planning->status = 'Cancelled';
            $planning->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Planning cancelled successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling planning: ' . $e->getMessage()
            ], 500);
        }
    }
}