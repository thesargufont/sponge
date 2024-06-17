<?php
  
namespace App\Http\Controllers;
  
use App\User;
use Exception;
use Carbon\Carbon;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DepartmentHist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    public function submitData(Request $request)
    {
        $departmentName = strtoupper($request->department_name);
        $description = strtoupper($request->description);

        if($departmentName == ''){
            return response()->json(['errors' => true, 
                            "message"=> '<div class="alert alert-danger">Nama bagian wajib terisi, harap periksa kembali formulir pengisian data</div>'
                    ]);    
        }
        
        if($description == ''){
            return response()->json(['errors' => true, 
                            "message"=> '<div class="alert alert-danger">Deskripsi wajib terisi, harap periksa kembali formulir pengisian data</div>'
                    ]);    
        }

        $checkDuplicateData = Department::where('department', $departmentName)
                                        ->where('active', 1)
                                        ->first();
                                             
        if($checkDuplicateData){
            return response()->json(['errors' => true, 
                            "message"=> '<div class="alert alert-danger">Telah ditemukan data bagian '.$departmentName.' yang masih aktif</div>'
                    ]);    
        }

        try {
            // CREATE DATA 
            DB::beginTransaction();
            
            $insertDepartment = new Department([
                'department'              => $departmentName,
                'department_description'  => $description,
                'active'                  => 1,
                'start_effective'         => Carbon::now(),
                'end_effective'           => null,
                'created_by'              => Auth::user()->id,
                'created_at'              => Carbon::now(),
                'updated_by'              => Auth::user()->id,
                'updated_at'              => Carbon::now(),
            ]);
            $insertDepartment->save();

            $insertDepartmentHist = new DepartmentHist([
                'department_id'           => $insertDepartment->id,
                'department'              => $insertDepartment->department,
                'department_description'  => $insertDepartment->department_description,
                'active'                  => $insertDepartment->active,
                'start_effective'         => $insertDepartment->start_effective,
                'end_effective'           => $insertDepartment->end_effective,
                'action'                  => 'CREATE',
                'created_by'              => Auth::user()->id,
                'created_at'              => Carbon::now(),
            ]);
            $insertDepartmentHist->save();

            DB::commit();
            return response()->json(['success' => true, 
                            "message"=> '<div class="alert alert-success">Data berhasil disimpan</div>'
                    ]);   
        } catch (Exception $e){
            DB::rollback();
            return response()->json(['errors' => true, 
                            "message"=> '<div class="alert alert-danger">Telah terjadi kesalahan sistem, data gagal diproses</div>'
                    ]);       
        }
    }

    public function getData($request,$isExcel='')
    {
        $department_name  = strtoupper($request->department_name);
        $status           = strtoupper($request->status);

        $departmentDatas = Department::where('active', $status);
        
        if($department_name != ''){
            $departmentDatas = $departmentDatas->where('department', $department_name);
        }
        
        return $departmentDatas;
    }

    public function data(Request $request)
    {
        $datas = $this->getData($request);

        $datatables = DataTables::of($datas)
            ->filter(function($instance) use ($request) {
                return true;
            });
        
        $datatables = $datatables->addColumn('action', function($item) use ($request){
            $txt = '';
            $txt .= "<a href=\"#\" onclick=\"showItem('$item[id]');\" title=\"" . ucfirst(__('view')) . "\" class=\"btn btn-xs btn-secondary\"><i class=\"fa fa-eye fa-fw fa-xs\"></i></a>";
            $txt .= "<a href=\"#\" onclick=\"editItem($item[id]);\" title=\"" . ucfirst(__('edit')) . "\" class=\"btn btn-xs btn-secondary\"><i class=\"fa fa-edit fa-fw fa-xs\"></i></a>";
            $txt .= "<a href=\"#\" onclick=\"deleteItem($item[id]);\" title=\"" . ucfirst(__('delete')) . "\" class=\"btn btn-xs btn-secondary\"><i class=\"fa fa-trash fa-fw fa-xs\"></i></a>";

            return $txt;
        })
        ->addColumn('active', function ($item) {
            if($item->active == 1){
                return 'AKTIF';
            } else {
                return 'TIDAK AKTIF';
            }
        })
        ->editColumn('start_effective', function ($item) {
            return Carbon::createFromFormat("Y-m-d H:i:s", $item->start_effective)->format('d/m/Y');
        })
        ->editColumn('end_effective', function ($item) {
            if($item->end_effective == null){
                return '-';
            } else {
                return Carbon::createFromFormat("Y-m-d H:i:s", $item->end_effective)->format('d/m/Y');
            }
        })
        ->addColumn('created_by', function ($item) {
            return optional($item->createdBy)->name;
        })
        ->editColumn('created_at', function ($item) {
            return Carbon::createFromFormat("Y-m-d H:i:s", $item->created_at)->format('d/m/Y H:i:s');
        })
        ->addColumn('updated_by', function ($item) {
            return optional($item->updatedBy)->name;
        })
        ->editColumn('updated_at', function ($item) {
            return Carbon::createFromFormat("Y-m-d H:i:s", $item->updated_at)->format('d/m/Y H:i:s');
        });

        return $datatables->make(TRUE);
    }
}