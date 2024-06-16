<?php
  
namespace App\Http\Controllers;
  
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
  
class DepartmentController extends Controller
{
    public function index()
    {
        return view('department.department_data');
    }

    public function createNew()
    {
        return view('department.create');
    }
}