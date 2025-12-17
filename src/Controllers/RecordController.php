<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Record;
use App\Helpers\Validator;

class RecordController extends Controller
{
    private Record $recordModel;
    private Validator $validator;
    
    public function __construct()
    {
        $this->recordModel = new Record();
        $this->validator = new Validator();
    }
    
    public function index()
    {
        $records = $this->recordModel->all();
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        
        $this->view('records/index', [
            'records' => $records,
            'message' => $message
        ]);
    }
    
    public function create()
    {
        $this->view('records/form', [
            'record' => null,
            'errors' => []
        ]);
    }
    
    public function store()
    {
        $data = $this->validateInput($_POST);
        
        if (!empty($data['errors'])) {
            $this->view('records/form', [
                'record' => $_POST,
                'errors' => $data['errors']
            ]);
            return;
        }
        
        $recordData = $this->prepareRecordData($data);
        
        if ($this->recordModel->create($recordData)) {
            $this->redirect('/records', 'Record created successfully!');
        } else {
            $this->redirect('/records', 'Failed to create record.');
        }
    }
    
    public function edit(int $id)
    {
        $record = $this->recordModel->find($id);
        
        if (!$record) {
            $this->redirect('/records', 'Record not found.');
            return;
        }
        
        $this->view('records/form', [
            'record' => $record,
            'errors' => []
        ]);
    }
    
    public function update(int $id)
    {
        $data = $this->validateInput($_POST);
        
        if (!empty($data['errors'])) {
            $record = $this->recordModel->find($id);
            $this->view('records/form', [
                'record' => array_merge($record, $_POST),
                'errors' => $data['errors']
            ]);
            return;
        }
        
        $recordData = $this->prepareRecordData($data);
        
        if ($this->recordModel->update($id, $recordData)) {
            $this->redirect('/records', 'Record updated successfully!');
        } else {
            $this->redirect('/records', 'Failed to update record.');
        }
    }
    
    public function destroy(int $id)
    {
        if ($this->recordModel->delete($id)) {
            $this->redirect('/records', 'Record deleted successfully!');
        } else {
            $this->redirect('/records', 'Failed to delete record.');
        }
    }
    
    private function validateInput(array $input): array
    {
        $data = [
            'first_name' => trim($input['first_name'] ?? ''),
            'middle_initial' => strtoupper(trim($input['middle_initial'] ?? '')),
            'last_name' => trim($input['last_name'] ?? ''),
            'loan' => filter_var($input['loan'] ?? '', FILTER_VALIDATE_FLOAT),
            'value' => filter_var($input['value'] ?? '', FILTER_VALIDATE_FLOAT),
            'errors' => []
        ];
        
        $this->validator->addRule('first_name', 'required|max:30', 'First name is required (max 30 characters).');
        $this->validator->addRule('last_name', 'required|max:30', 'Last name is required (max 30 characters).');
        $this->validator->addRule('loan', 'required|numeric', 'Loan amount is required and must be numeric.');
        $this->validator->addRule('value', 'required|numeric|min:0.01', 'Value is required and must be at least 0.01.');
        
        if (!empty($data['middle_initial'])) {
            $this->validator->addRule('middle_initial', 'alpha|max:1', 'Middle initial must be a single letter.');
        }
        
        $data['errors'] = $this->validator->validate($data);
        
        return $data;
    }
    
    private function prepareRecordData(array $data): array
    {
        $ltv = $this->recordModel->calculateLTV($data['loan'], $data['value']);
        
        return [
            'first_name' => $data['first_name'],
            'middle_initial' => $data['middle_initial'],
            'last_name' => $data['last_name'],
            'loan' => $data['loan'],
            'value' => $data['value'],
            'ltv' => $ltv
        ];
    }
}