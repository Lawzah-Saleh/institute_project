@if($students->isEmpty())
    <p class="text-danger text-center">❌ لا يوجد طلاب في هذه الجلسة</p>
@else
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>الطالب</th>
            <th>درجة العملي</th>
            <th>درجة النهائي</th>
            <th>درجة الحضور</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->student_name_ar }}</td>
            <td><input type="number" name="practical_degree[{{ $student->id }}]" class="form-control"></td>
            <td><input type="number" name="final_degree[{{ $student->id }}]" class="form-control"></td>
            <td><input type="number" name="attendance_degree[{{ $student->id }}]" class="form-control" value="{{ $student->attendance_degree }}" readonly></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
