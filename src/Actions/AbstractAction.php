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
        return $this->dataType;
    }

    public function getPolicy()
    {
    }

    public function getTitle()
    {
    }

    public function getIcon()
    {
    }

    public function getDefaultRoute()
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
        $dt = $this->getDataType(); // string|null

        return $dt === null || ($this->dataType?->name === $dt);
    }

    public function shouldActionDisplayOnRow($row)
    {
        return true;
    }
}
