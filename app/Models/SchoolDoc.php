<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDoc extends Model
{
    use HasFactory;

    // ? Table
    protected $table = 'schooldoc';

    public $must_columns = [
        "name-of-county", "sub-counties", "name-of-assessor", "name-of-school", "electricity", "internet", "ict-teacher", "learners-name", "assessment-number", "year-of-birth", "gender", "name-of-parent-guardian", "parent-guardian-phone-number", "visual-ability", "reccomendation-1", "reading-ability", "reccomendation-2", "physical-ability", "recommendation-3"
    ];

    /**
     * Todo: School Document
     *
     * ? Read the school document
     * ? Pass the document id
     *
     * @param int $doc (optional)
     * @param string $doc_path (optional)
     *
     * @return array
     */
    public static function read(int $doc = null, string $doc_path = null): array
    {

        // ? Check Path  & Doc if is null
        if ($doc_path == null && $doc == null) {
            // Return error
            return [];
        }

        // ? If not null Doc
        if ($doc != null) {
            // ? Get the document
            $doc = Self::where('id', $doc)->first();
            // ? If not found
            if ($doc == null) {
                // Return error
                return [];
            }
            // ? Pick the path
            $doc_path = $doc->path;
        }

        // ? File path
        $spread_sheet_doc = $doc_path;

        // Todo: Open and Read the Excel File
        // ? Open the file
        $spread_sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($spread_sheet_doc);

        // ? Get the first sheet
        $sheet = $spread_sheet->getActiveSheet();

        // ? Get the highest row number and column letter referenced in the worksheet
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // ? Get the highest column number
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // ? Read a row of data into an array
        $headerColumns = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

        // ? If the header is empty, return error
        if (empty($headerColumns[0])) {
            return [];
        }


        // ? remove Empty/null Array from the header
        $column_names = [];
        $headerColumns = array_filter($headerColumns[0]);
        foreach ($headerColumns as $index => $headerName) {
            // ? trim the header name,
            $headerName = trim($headerName);
            // ? remove special characters from the header name with -
            $headerName = preg_replace('/[^A-Za-z0-9\-]/', '-', $headerName);
            // ? Replace space with -
            $headerName = str_replace(' ', '-', $headerName);
            // ? remove multiple - from the header name
            $headerName = preg_replace('/-+/', '-', $headerName);
            // ? lowercase the header name
            $headerName = strtolower($headerName);
            // ? add the header name to the column names
            $column_names[$index] = $headerName;
        }

        // ? Check if the must columns are present
        $must_columns = (new Self())->must_columns;
        $missing_columns = array_diff($must_columns, $column_names);

        // ? If there are missing columns, return error
        if (count($missing_columns) > 0) {
            return [];
        }

        // ? Document Data
        $document_data = [];

        // ? Loop through each row of the worksheet in turn
        for ($row = 2; $row <= $highestRow; $row++) {
            // ? Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            // ? Add the data to the document data
            foreach ($column_names as $index => $this_column) {
                $row_column[$this_column] = trim($rowData[0][$index]);
            }

            // ? Extract phone number,
            foreach ($row_column as $key => $value) {
                // ? Keys to skip
                $skip_keys = ['reccomendation-1', 'reccomendation-2', 'reccomendation-3', 'recommendation-1', 'recommendation-2', 'recommendation-3'];
                if (in_array($key, $skip_keys)) {
                    // ? Remove the key
                    unset($row_column[$key]);
                    // ? Skip
                    continue;
                }
                // ? Pick - name-of-assessor
                elseif ($key == 'name-of-assessor') {
                    // ? Explode the value by  -
                    $name_of_assessor_array = explode("-", $value);
                    // ? Get the last array
                    $assessor_phone = (count($name_of_assessor_array) > 1) ? end($name_of_assessor_array) : '';
                    // ? Join the array apart from the last array
                    $assessor_name = (count($name_of_assessor_array) > 1) ? implode(" ", array_slice($name_of_assessor_array, 0, -1)) : $value;
                    // ? Add the column to the document data
                    $row_column[$key] = [
                        'name' => $assessor_name,
                        'phone' => $assessor_phone,
                        'default' => $value,
                    ];
                }

                // ? Pick - name-of-school
                elseif ($key == 'name-of-school') {
                    // ? Default
                    $deafault_string = $value;
                    $value = 'Limuru Mission Primary(HT:Jacqueline Njuku |TEL:0721527630 |EMAIL:0721527630)';
                    // ? Get string between ( and )
                    preg_match_all('/\((.*?)\)/', $value, $matches);
                    $more_info = $matches[1][0];
                    // ? Get string outside ( and )
                    $name_of_school = preg_replace('/\((.*?)\)/', '', $value);

                    // ? $more_info has data in form HT:Jacqueline Njuku |TEL:0721527630 |EMAIL:0721527630. Extract the data as ht => Jacqueline Njuku, tel => 0721527630, email => 0721527630
                    $more_info_array = explode("|", $more_info);
                    $more_info_array = array_map('trim', $more_info_array);
                    $more_info_array = array_filter($more_info_array);
                    $more_info_array = array_map(function ($value) {
                        return explode(":", $value);
                    }, $more_info_array);

                    // ? Convert the array to an associative array
                    $more_info_array = array_reduce($more_info_array, function ($carry, $item) {
                        // ? Trim and lowercase the key
                        $item[0] = strtolower(trim($item[0]));
                        // ? If the array has two items, add the first item as key and the second item as value
                        $carry[$item[0]] = $item[1];
                        return $carry;
                    }, []);

                    // ? Loop through the more_info_array check for key ht, tel, email if not exist create it with null value
                    foreach (['ht', 'tel', 'email'] as $key_more) {
                        if (!array_key_exists($key_more, $more_info_array)) {
                            $more_info_array[$key_more] = null;
                        }
                    }

                    // ? Add School
                    $more_info_array['school'] = $name_of_school;
                    $more_info_array['default'] = $deafault_string;
                    // ? Add the column to the document data
                    $row_column[$key] = $more_info_array;
                }

                // ? Pick - visual-ability
                elseif ($key == 'visual-ability') {
                    // ? Default
                    $state = $value;
                    // ? Response
                    $response = (array_key_exists('reccomendation-1', $row_column)) ? $row_column['reccomendation-1'] : null;
                    $response = (array_key_exists('recommendation-1', $row_column)) ? $row_column['recommendation-1'] : $response;

                    // ? Assign
                    $row_column[$key] = [
                        'state' => $state,
                        'response' => $response,
                    ];
                }

                // ? Pick - reading-ability
                elseif ($key == 'reading-ability') {
                    // ? Default
                    $state = $value;
                    // ? Response
                    $response = (array_key_exists('reccomendation-2', $row_column)) ? $row_column['reccomendation-2'] : null;
                    $response = (array_key_exists('recommendation-2', $row_column)) ? $row_column['recommendation-2'] : $response;

                    // ? Assign
                    $row_column[$key] = [
                        'state' => $state,
                        'response' => $response,
                    ];
                }

                // ? Pick - physical-ability
                elseif ($key == 'physical-ability') {
                    // ? Default
                    $state = $value;
                    // ? Response
                    $response = (array_key_exists('reccomendation-3', $row_column)) ? $row_column['reccomendation-3'] : null;
                    $response = (array_key_exists('recommendation-3', $row_column)) ? $row_column['recommendation-3'] : $response;

                    // ? Assign
                    $row_column[$key] = [
                        'state' => $state,
                        'response' => $response,
                    ];
                }
            }

            // ? Add the column to the document data
            $document_data[] = $row_column;
        }

        // ? Return the document data
        return $document_data;
    }
}
