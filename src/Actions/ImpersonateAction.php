<?php

namespace TCG\Voyager\Actions;

class ImpersonateAction extends AbstractAction
{
    public function getTitle()
    {
        return __('voyager::generic.impersonate');
    }

    public function getIcon()
    {
        return 'voyager-pirate';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right impersonate',
        ];
    }

    public function shouldActionDisplayOnDataType():bool
    {
        return $this->dataType->slug == 'users';
    }

    public function getDefaultRoute()
    {
        return route('voyager.impersonate.impersonate', ['user_id' => $this->data->{$this->data->getKeyName()}]);
    }
}