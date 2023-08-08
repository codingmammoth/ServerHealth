<?php

class ServerHealthResult
{
    private string $name = '';
    private string $status = '';
    private string|null $description = null;
    private int|float|null $value = null;

    public function getResult(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'value' => $this->value,
            'status' => $this->status
        ];
    }
    
    public function __construct($name, $status, $description = null, $value = null)
    {
        $this->name = $name;
        $this->status = $status;
        $this->description = $description;
        $this->value = $value;
    }
}
