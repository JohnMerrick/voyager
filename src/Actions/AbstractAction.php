<?php

namespace TCG\Voyager\Actions;

abstract class AbstractAction implements ActionInterface
{
    protected $dataType;
    protected $data;

    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
    }

    public function getDataType()
    {
    }

    public function getPolicy()
    {
    }


    public function getRoute($key)
    {
        if (method_exists($this, $method = 'get'.ucfirst($key).'Route')) {
            return $this->$method();
        } else {
            return $this->getDefaultRoute();
        }
    }

    public function getAttributes()
    {
        return [];
    }

    public function convertAttributesToHtml()
    {
        $result = [];

        foreach ($this->getAttributes() as $key => $attribute) {
            $result[] = sprintf('%s="%s"', $key, $attribute);
        }

        return implode(" ", $result);
    }

    public function shouldActionDisplayOnDataType(): bool
    {
        $dt = $this->getDataType();

        if ($dt === null) {
            return true;
        }

        $dataTypeName = is_string($dt) ? $dt : ($dt->name ?? null);

        return $this->dataType?->name === $dataTypeName;
    }

    public function shouldActionDisplayOnRow($row)
    {
        return true;
    }
}
