<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasEmailPlaceholders
{
    /**
     * Process placeholders in a template string using data from the model or an array.
     */
    public function processPlaceholders(string $template, $data = null): string
    {
        $data = $data ?? $this;
        $placeholders = $this->getPlaceholderValues($data);

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }

    /**
     * Define the actual values for each placeholder.
     * This can be overridden in the model/mailable for specific logic.
     */
    protected function getPlaceholderValues($data): array
    {
        $values = [
            '{site_name}' => config('app.name'),
            '{year}' => date('Y'),
        ];

        if ($data instanceof Model) {
            foreach ($data->getAttributes() as $key => $value) {
                $values["{{$key}}"] = e($value);
            }
            
            // Add common related data if applicable
            if (method_exists($data, 'getInterestNameAttribute')) {
                $values['{service_type}'] = e($data->getInterestNameAttribute());
                $values['{event_type}'] = e($data->getInterestNameAttribute());
            }
        } elseif (is_array($data)) {
            foreach ($data as $key => $value) {
                $values["{{$key}}"] = e($value);
            }
        }

        return $values;
    }
}
