<?php


namespace App\Services\Html\Concerns;

use Spatie\Html\Elements\Div;
use Illuminate\Support\ViewErrorBag;


trait Alerts
{
    public function alert($type, $message)
    {
        return $this->div()
                    ->class($type ? "alert--{$type}" : 'alert')
                    ->html($message);
    }

    public function flashMessage()
    {
        if (
            ! $this->request->session()->get('flash_notification.0.level') ||
            ! $this->request->session()->get('flash_notification.0.message')
        ) {
            return null;
        }

        return $this->alert(
            $this->request->session()->get('flash_notification.0.level'),
            $this->request->session()->get('flash_notification.0.message')
        );
    }

    public function error($message = '', $field = '')
    {
        if ( ! $message) {
            return null;
        }

        return $this->alert('danger', $message)
                    ->attributeIf($field, 'data-validation-error', $field);
    }

    public function errorFor($field)
    {
        return $this->error(
            session('errors', new ViewErrorBag())->first($this->fieldName($field)),
            $this->fieldName($field)
        );
    }

    public function message($message = '')
    {
        return $this->alert('success', $message);
    }

    public function info($message = '')
    {
        return $this->alert(
            'info',
            $this->icon('info-circle') . ' ' . $message
        );
    }

    public function warning($message = '')
    {
        return $this->alert(
            'warning',
            $this->icon('exclamation-triangle') . ' ' . $message
        );
    }
}