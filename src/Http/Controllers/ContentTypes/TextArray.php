<?php

namespace TCG\Voyager\Http\Controllers\ContentTypes;

class TextArray extends BaseType
{
    /**
     * @return null|string
     */
    public function handle()
    {
        $value = $this->request->input($this->row->field);

        if (isset($this->options->null)) {
            return $value == $this->options->null ? null : $value;
        }

        return explode("\n", $value );
    }
}
