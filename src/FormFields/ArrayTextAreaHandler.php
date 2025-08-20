<?php

namespace TCG\Voyager\FormFields;

class ArrayTextAreaHandler extends AbstractHandler
{
    protected $codename = 'array_text_area';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        

        return view('voyager::formfields.array_text_area', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
