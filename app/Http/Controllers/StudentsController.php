<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    // GET /students : daftar mahasiswa
    public function index()
    {
        $students = Student::orderBy('id', 'DESC')->paginate(10);

        return response()->json($students, 200);
    }

    // GET /students/{id} : satu mahasiswa
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student, 200);
    }

    // POST /students : tambah mahasiswa
    public function store(Request $request)
    {
        $this->validate($request, [
            'nim'        => 'required|max:20|unique:students,nim',
            'name'       => 'required|max:100',
            'email'      => 'required|email|max:100|unique:students,email',
            'phone'      => 'required|max:20',
            'address'    => 'required',
            'gender'     => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'major'      => 'required|max:100',
        ]);

        $input   = $request->all();
        $student = Student::create($input);

        return response()->json($student, 201);
    }

    // PUT /students/{id} : ubah mahasiswa
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $this->validate($request, [
            'nim'        => 'max:20|unique:students,nim,' . $id,
            'name'       => 'max:100',
            'email'      => 'email|max:100|unique:students,email,' . $id,
            'phone'      => 'max:20',
            'gender'     => 'in:Laki-laki,Perempuan',
            'birth_date' => 'date',
            'major'      => 'max:100',
        ]);

        $input = $request->all();
        $student->fill($input);
        $student->save();

        return response()->json($student, 200);
    }

    // DELETE /students/{id} : hapus mahasiswa
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();
        $message = ['message' => 'deleted successfully', 'student_id' => $id];

        return response()->json($message, 200);
    }
}