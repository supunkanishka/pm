<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;

class TasksImport implements ToModel,FromCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new Task([
        //     'name'     => $row[0],
        //     'description'    => $row[1], 
        //     'project_id'    => $row[2], 
        // ]);
    }

    public function collection()
    {
        return Task::all();
    }
}
