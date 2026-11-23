<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AdminEmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('job')->orderBy('Id', 'desc')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }
    public function active()
    {
        $employees = Employee::with('job')->active()->orderBy('Id', 'desc')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }
    public function blocked()
    {
        $employees = Employee::with('job')->blocked()->orderBy('Id', 'desc')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }
    public function create()
    {
        $jobTitles = JobTitle::orderBy('Name')->get();
        return view('admin.employees.create', compact('jobTitles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'surname' => 'required|string|max:30',
            'name' => 'required|string|max:30',
            'patronymic' => 'nullable|string|max:30',
            'email' => 'required|email|max:50|unique:employee,email',
            'login' => 'required|string|max:30|unique:employee,login',
            'password' => 'required|string|min:6',
            'job_id' => 'required|exists:job_title,Id',
        ]);
        $employee = Employee::create([
            'Surname' => $request->surname,
            'Name' => $request->name,
            'Patronymic' => $request->patronymic,
            'email' => $request->email,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'Job_Id' => $request->job_id,
            'IsBlocked' => false,
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Сотрудник {$employee->Surname} {$employee->Name} добавлен");
    }
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $jobTitles = JobTitle::orderBy('Name')->get();
        return view('admin.employees.edit', compact('employee', 'jobTitles'));
    }
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $request->validate([
            'surname' => 'required|string|max:30',
            'name' => 'required|string|max:30',
            'patronymic' => 'nullable|string|max:30',
            'email' => 'required|email|max:50|unique:employee,email,' . $id . ',Id',
            'login' => 'required|string|max:30|unique:employee,login,' . $id . ',Id',
            'password' => 'nullable|string|min:6',
            'job_id' => 'required|exists:job_title,Id',
        ]);

        $employee->Surname = $request->surname;
        $employee->Name = $request->name;
        $employee->Patronymic = $request->patronymic;
        $employee->email = $request->email;
        $employee->login = $request->login;
        $employee->Job_Id = $request->job_id;
        
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }
        
        $employee->save();

        return redirect()->route('admin.employees.index')
            ->with('success', "Сотрудник {$employee->Surname} {$employee->Name} обновлен");
    }
    public function ban($id)
    {
        $employee = Employee::findOrFail($id);
        
        if (auth()->guard('employee')->id() == $id) {
            return redirect()->route('admin.employees.index')
                ->with('error', 'Вы не можете заблокировать свой собственный аккаунт');
        }
        
        $employee->IsBlocked = true;
        $employee->save();

        return redirect()->route('admin.employees.index')
            ->with('success', "Сотрудник {$employee->Surname} {$employee->Name} заблокирован");
    }
    public function unban($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->IsBlocked = false;
        $employee->save();

        return redirect()->route('admin.employees.index')
            ->with('success', "Сотрудник {$employee->Surname} {$employee->Name} разблокирован");
    }
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        if (auth()->guard('employee')->id() == $id) {
            return redirect()->route('admin.employees.index')
                ->with('error', 'Вы не можете удалить свой собственный аккаунт');
        }
        
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', "Сотрудник удален");
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $employees = Employee::with('job')
            ->where(function($query) use ($search) {
                $query->where('Surname', 'like', "%{$search}%")
                      ->orWhere('Name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('login', 'like', "%{$search}%");
            })
            ->orderBy('Id', 'desc')
            ->paginate(15);
        
        return view('admin.employees.index', compact('employees'));
    }
}